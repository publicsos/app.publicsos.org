<?php
declare(strict_types=1);


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Mail\Models\EmailServiceType;
use Modules\Mail\Models\UnsubscribeEventType;
use Modules\Mail\UpgradeMigration;

return new class extends  UpgradeMigration
{
    public function up()
    {

        // Create email service types table and seed
        Schema::create('email_service_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        $this->seedEmailServiceTypes();

        // Create email services table
        Schema::create('email_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->string('name')->nullable();
            $table->unsignedInteger('type_id');
            $table->mediumText('settings');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('email_service_types');
        });

        // Create unsubscribe event types table and seed
        $this->createUnsubscribeEventTypesTable();

        // Create campaign statuses table and seed
        $this->createCampaignStatusesTable();

        // Create campaign tags table and seed
        //$this->createCampaignTagsTable();

        // Create subscribers, segments, templates, campaigns, messages, and related tables
        $this->createSubscribersTable();
        $this->createSegmentsTable();
        $this->createTemplatesTable();
        $this->createCampaignsTable();
        $this->createMessagesTable();

        $this->createSubscribersSegmentsTable();

        $this->createContactSegmentsTable();
        // Rename and modify existing tables as needed
        $this->renameAndModifyTables();

        $this->createWorkflowsTable();
    }

    protected function seedEmailServiceTypes()
    {
        $serviceTypes = [
            ['id' => EmailServiceType::SES, 'name' => 'SES'],
            ['id' => EmailServiceType::SENDGRID, 'name' => 'SendGrid'],
            ['id' => EmailServiceType::MAILGUN, 'name' => 'Mailgun'],
            ['id' => EmailServiceType::POSTMARK, 'name' => 'Postmark'],
            ['id' => EmailServiceType::MAILJET, 'name' => 'Mailjet'],
            ['id' => EmailServiceType::SMTP, 'name' => 'SMTP'],
            ['id' => EmailServiceType::POSTAL, 'name' => 'Postal'],
            ['id' => EmailServiceType::ZEPTO, 'name' => 'ZeptoMail'],
            ['id' => EmailServiceType::TRACK, 'name' => 'SmtpTrack'],
            ['id' => EmailServiceType::PRINT, 'name' => 'PrintMail']
        ];

        foreach ($serviceTypes as $type) {
            DB::table('email_service_types')->insert($type + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    protected function createUnsubscribeEventTypesTable()
    {
        Schema::create('unsubscribe_event_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $types = [
            UnsubscribeEventType::BOUNCE => 'Bounce',
            UnsubscribeEventType::COMPLAINT => 'Complaint',
            UnsubscribeEventType::MANUAL_BY_ADMIN => 'Manual by Admin',
            UnsubscribeEventType::MANUAL_BY_SUBSCRIBER => 'Manual by Subscriber',
            UnsubscribeEventType::AUTOMATIC_BY_VALIDATOR => 'Automatic by Validator',
        ];

        foreach ($types as $id => $name) {
            DB::table('unsubscribe_event_types')->insert(['id' => $id, 'name' => $name]);
        }
    }

    protected function createCampaignStatusesTable()
    {
        Schema::create('campaign_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        DB::table('campaign_statuses')->insert([
            ['name' => 'Draft'],
            ['name' => 'Queued'],
            ['name' => 'Sending'],
            ['name' => 'Sent'],
            ['name' => 'Cancelled'],
        ]);
    }

    protected function createCampaignTagsTable()
    {

        $segments = $this->getTableName('tags');
        $campaigns = $this->getTableName('campaigns');

        Schema::create('campaign_tag', function (Blueprint $table) use ($campaigns, $segments) {
            $table->increments('id');
            $table->unsignedInteger('segment_id');
            $table->unsignedInteger('campaign_id');
            $table->timestamps();

            $table->foreign('tag_id')->references('id')->on($segments);
            $table->foreign('campaign_id')->references('id')->on($campaigns);
        });
    }


    protected function createSubscribersTable()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->uuid('hash')->unique();
            $table->string('email')->index();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestamp('unsubscribed_at')->nullable()->index();
            $table->unsignedInteger('unsubscribe_event_id')->nullable();
            $table->timestamps();

            $table->foreign('unsubscribe_event_id')->references('id')->on('unsubscribe_event_types');
        });
    }

    protected function createSegmentsTable()
    {
        Schema::create('segments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    protected function createTemplatesTable()
    {

        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('premium')->default(false); // Represents the 'premium' field
            $table->string('rating')->nullable(); // Represents the 'rating' field
            $table->string('previewUrl')->nullable(); // Represents the 'previewUrl' field
            $table->string('slug')->nullable(); // Represents the 'slug' field
            $table->json('votes')->nullable(); // Represents the 'votes' field
            $table->string('type')->default('email'); // Represents the 'type' field
            $table->timestamps();
        });
    }

    protected function createCampaignsTable()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->string('name');
            $table->unsignedInteger('status_id')->default(1);
            $table->unsignedInteger('template_id')->nullable();
            $table->unsignedInteger('email_service_id')->nullable();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->boolean('is_open_tracking')->default(true);
            $table->boolean('is_click_tracking')->default(true);
            $table->mediumInteger('sent_count')->nullable()->default(0);
            $table->mediumInteger('open_count')->nullable()->default(0);
            $table->mediumInteger('click_count')->nullable()->default(0);
            $table->boolean('send_to_all')->default(false);
            $table->boolean('save_as_draft')->default(true);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('campaign_statuses');
            $table->foreign('template_id')->references('id')->on('templates');
            $table->foreign('email_service_id')->references('id')->on('email_services');
        });
    }

    protected function createMessagesTable()
    {

        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('hash')->unique();
            $table->unsignedInteger('workspace_id')->index();
            $table->unsignedInteger('subscriber_id')->index();
            $table->string('source_type')->index();
            $table->unsignedInteger('source_id')->index();
            $table->string('recipient_email');
            $table->string('subject');
            $table->string('from_name');
            $table->string('from_email');
            $table->string('message_id')->index()->nullable();
            $table->string('ip')->nullable();
            $table->unsignedInteger('open_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamp('queued_at')->nullable()->default(null)->index();
            $table->timestamp('sent_at')->nullable()->default(null)->index();
            $table->timestamp('delivered_at')->nullable()->default(null)->index();
            $table->timestamp('bounced_at')->nullable()->default(null)->index();
            $table->timestamp('unsubscribed_at')->nullable()->default(null)->index();
            $table->timestamp('complained_at')->nullable()->default(null)->index();
            $table->timestamp('opened_at')->nullable()->default(null)->index();
            $table->timestamp('clicked_at')->nullable()->default(null)->index();
            $table->timestamps();
        });

        $messages = $this->getTableName('messages');

        Schema::create('message_failures', function (Blueprint $table) use ($messages) {
            $table->bigIncrements('id');
            $table->unsignedInteger('message_id');
            $table->string('severity')->nullable()->default(null);
            $table->mediumText('description')->nullable()->default(null);
            $table->timestamp('failed_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on($messages);
        });
    }

    public function createSubscribersSegmentsTable()
    {
        $segments = $this->getTableName('segments');
        $subscribers = $this->getTableName('subscribers');

        Schema::create('segment_subscriber', function (Blueprint $table) use ($segments, $subscribers) {
            $table->increments('id');
            $table->unsignedInteger('segment_id');
            $table->unsignedInteger('subscriber_id');
            $table->timestamps();

            $table->foreign('segment_id')->references('id')->on($segments);
            $table->foreign('subscriber_id')->references('id')->on($subscribers);
        });
    }

    protected function createContactSegmentsTable()
    {
        $segments = $this->getTableName('segments');
        $campaigns = $this->getTableName('campaigns');

        Schema::create('campaign_segment', function (Blueprint $table) use ($campaigns, $segments) {
            $table->increments('id');
            $table->unsignedInteger('segment_id');
            $table->unsignedInteger('campaign_id');
            $table->timestamps();

            $table->foreign('segment_id')->references('id')->on($segments);
            $table->foreign('campaign_id')->references('id')->on($campaigns);
        });
    }

    protected function renameAndModifyTables()
    {
        Schema::rename('segments', 'tags');

        //dd(Schema::getTables());

        Schema::table('segment_subscriber', function (Blueprint $table) {
            $table->renameColumn('segment_id', 'tag_id');
            $table->foreign('tag_id')->references('id')->on('tags');
        });


        Schema::table('campaign_segment', function (Blueprint $table) {
            $table->renameColumn('segment_id', 'tag_id');
            $table->foreign('tag_id')->references('id')->on('tags');
        });

        Schema::rename('segment_subscriber', 'tag_subscriber');
        Schema::rename('campaign_segment', 'campaign_tag');
    }


    protected function createWorkflowsTable()
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->default(1);
            $table->string('name');

            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('workflow_id')->nullable();
            $table->bigInteger('parentable_id')->nullable()->index();
            $table->string('parentable_type')->nullable()->index();
            $table->string('type');
            $table->string('name');
            $table->json('data_fields')->nullable();
            $table->json('conditions')->nullable();
            $table->integer('node_id')->nullable();
            $table->integer('pos_x')->default(0);
            $table->integer('pos_y')->default(0);
            $table->timestamps();
        });
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('workflow_log_id');
            $table->bigInteger('task_id');
            $table->string('name');
            $table->string('status');
            $table->text('message')->nullable();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->timestamps();
        });
        Schema::create('triggers', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->boolean('queueable')->default(true);
            $table->json('data_fields')->nullable();
            $table->json('conditions')->nullable();
            $table->bigInteger('workflow_id')->nullable()->index();
            $table->integer('pos_x');
            $table->integer('pos_y');
            $table->timestamps();
        });
        Schema::create('workflow_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('workflow_id')->nullable()->index();
            $table->bigInteger('elementable_id')->nullable()->index();
            $table->string('elementable_type')->nullable()->index();
            $table->bigInteger('triggerable_id')->nullable()->index();
            $table->string('triggerable_type')->nullable()->index();
            $table->string('name');
            $table->string('status');
            $table->text('message')->nullable();
            $table->text('databus')->nullable();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->timestamps();
        });
        Schema::create('message_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source_type')->index();
            $table->unsignedInteger('source_id')->index();
            $table->string('hash')->index();
            $table->string('url')->index();
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamps();
        });

//        Schema::table('task_logs', function (Blueprint $table) {
//            $table->bigInteger('task_id')->unsigned()->change();
//            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
//        });
//
//        Schema::table('tasks', function (Blueprint $table) {
//         //   $table->dropIndex(['workflow_id']);
//            $table->bigInteger('workflow_id')->unsigned()->change();
//            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
//        });
//
//        Schema::table('triggers', function (Blueprint $table) {
////            $table->bigInteger('workflow_id')->unsigned()->change();
//            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
//        });
//
//        Schema::table('workflow_logs', function (Blueprint $table) {
//           /// $table->bigInteger('workflow_id')->unsigned()->change();
//            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
//        });
    }

};
