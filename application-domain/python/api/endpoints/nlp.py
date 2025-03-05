from fastapi import APIRouter, HTTPException, File, UploadFile
from pydantic import BaseModel
import spacy
import os
import pymupdf4llm

from newspaper import Article, Config
from markdownify import markdownify as md
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
import yake
import socials
import socid_extractor
import socialshares
from spacy import displacy

# Initialize FastAPI Router
router = APIRouter()

# Load SpaCy Model
nlp = spacy.load("en_core_web_trf")

# Directory to save the uploaded PDFs
UPLOAD_DIRECTORY = "pdfs"
os.makedirs(UPLOAD_DIRECTORY, exist_ok=True)

# Initialize Sentiment Analyzer
sentiment_analyzer = SentimentIntensityAnalyzer()

# Constants
EXCLUDED_ENTITY_TYPES = {}

# Request Models
class ArticleAction(BaseModel):
    link: str

class SummarizeAction(BaseModel):
    text: str

# Helper Functions
def filter_entities(doc):
    return list(dict.fromkeys((ent.label_, ent.text) for ent in doc.ents if ent.label_ not in EXCLUDED_ENTITY_TYPES))

def fetch_article(link: str) -> Article:
    """Fetch and parse an article using Newspaper."""
    try:
        config = Config()
        config.browser_user_agent = "NLP/0.0.1 (Unix; Intel) Chrome/123.0.0"
        config.request_timeout = 10
        config.fetch_images = True
        config.memoize_articles = True
        config.follow_meta_refresh = True

        article = Article(link, config=config, keep_article_html=True)
        article.download()
        article.parse()
        return article
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Failed to fetch article: {str(e)}")

def extract_keywords(text: str, language: str = "en", n: int = 1, dedup_lim: float = 0.9, top: int = 5):
    """Extract keywords using YAKE."""
    extractor = yake.KeywordExtractor(lan=language, n=n, dedupLim=dedup_lim, top=top)
    return sorted(extractor.extract_keywords(text), key=lambda x: x[1])

def perform_social_analysis(link: str, text: str):
    """Perform social media and sentiment analysis."""
    try:
        return {
            "social_accounts": socials.extract(link).get_matches_per_platform(),
            "social_shares": socialshares.fetch(link, platforms=["facebook", "pinterest", "linkedin", "google", "reddit"]),
            "sentiment": sentiment_analyzer.polarity_scores(text),
            "accounts": socid_extractor.extract(text),
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Social analysis failed: {str(e)}")

# Endpoints
@router.post("/nlp/article")
async def process_article(article: ArticleAction):
    try:
        fetched_article = fetch_article(article.link)
        doc = nlp(fetched_article.text)
        filtered_entities = filter_entities(doc)
        social_analysis = perform_social_analysis(article.link, fetched_article.text)
        spacy_html = displacy.render(doc, style="ent", options={"ents": [e[0] for e in filtered_entities]})
        keywords = extract_keywords(fetched_article.text, top=5)
        return {
            "data": {
                "title": fetched_article.title,
                "date": fetched_article.publish_date,
                "text": fetched_article.text,
                "markdown": md(fetched_article.article_html, newline_style="BACKSLASH", strip=["a"], heading_style="ATX"),
                "html": fetched_article.article_html,
                "summary": fetched_article.summary,
                "keywords": keywords,
                "authors": fetched_article.authors,
                "banner": fetched_article.top_image,
                "images": fetched_article.images,
                "entities": filtered_entities,
                "videos": fetched_article.movies,
                "social": social_analysis["social_accounts"],
                "spacy": spacy_html,
                "spacy_markdown": md(spacy_html, newline_style="BACKSLASH", strip=["a"], heading_style="ATX"),
                "sentiment": social_analysis["sentiment"],
                "accounts": social_analysis["accounts"],
                "social_shares": social_analysis["social_shares"],
            }
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error processing article: {str(e)}")

@router.post("/nlp/tags")
async def extract_tags(article: SummarizeAction):
    try:
        return {"data": extract_keywords(article.text, n=3, top=5)}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Keyword extraction failed: {str(e)}")

@router.post("/nlp/pdf-reader/")
async def upload(file: UploadFile = File(...)):
    if file.content_type != "application/pdf" or not file.filename.endswith(".pdf"):
        raise HTTPException(status_code=400, detail="Only PDF files are allowed.")
    try:
        file_path = os.path.join(UPLOAD_DIRECTORY, file.filename)
        with open(file_path, "wb") as f:
            f.write(file.file.read())
        markdown_text = pymupdf4llm.to_markdown(file_path)
        doc = nlp(markdown_text)
        return {"message": f"Successfully uploaded {file.filename}", "markdown": markdown_text, "entities": filter_entities(doc)}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error processing the file: {e}")
    finally:
        file.file.close()
