<?php
declare(strict_types=1);
namespace Modules\Mail\Fields;

/**
 * Trait Fieldable.
 *
 * Access the Data Fields of an Element.
 */
trait Fieldable
{
    /**
     * Return the Field value. If Field is not existing it returns the Field name.
     *
     * @param  string  $field
     * @return string
     */
    public function getFieldValue(mixed $field): string
    {
        info('Getting Field Value '.$field);
        if (empty($field)) {
            return '';
        }

        if (! isset($this->data_fields[$field])) {
            return '';
        }

        return $this->data_fields[$field]['value'] ?? '';
    }

    /**
     * Returns the Field Type. If Field is not existing it returns an empty String.
     *
     * @param  string  $field
     * @return string
     */
    public function getFieldType(string $field): string
    {
        info('Getting Field Type '.$field);
        return $this->data_fields[$field]['type'] ?? '';
    }

    /**
     * Check if the Field is from the passed resourceType.
     *
     * @param  string  $field
     * @param  string  $resourceType
     * @return bool
     */
    public function fieldIsResourceType(string $field, string $resourceType): bool
    {
        info('Checking if Field '.$field.' is of type '.$resourceType);
        return $this->getFieldType($field) === $resourceType;
    }

    /**
     * Pass selected back if the resourceType is selected for this field. If not an empty String.
     *
     * @param  string  $field
     * @param  string  $resourceType
     * @return string
     */
    public function fieldIsSelected(string $field, string $resourceType): string
    {
        info('Checking if Field '.$field.' is selected for type '.$resourceType);
        return $this->fieldIsResourceType($field, $resourceType) ? 'selected' : '';
    }

    /**
     * Loads Resource Intelligence from the corresponding DataResourceClass.
     * If non is set its taking the first defined one from Config.
     *
     * @param  string  $field
     * @return string
     */
    public function loadResourceIntelligence(string $field): string
    {
        info('Loading Resource Intelligence for '.$field);
        if (! isset($this->data_fields[$field])) {
            info('No Data Fields found for '.$field);
            //todo throw exception
            $resources = [
                'ValueResource' => \Modules\Mail\DataBuses\ValueResource::class,
                'ModelResource' => \Modules\Mail\DataBuses\ModelResource::class,
                'DataResource' => \Modules\Mail\DataBuses\DataBusResource::class,
                'ConfigResource' => \Modules\Mail\DataBuses\ConfigResource::class,
            ];
            $class = reset($resources);
        } else {
            info('Loading Resource Intelligence for '.$field);
            $className = $this->getFieldType($field);
            $class = new $className();


        }

        info('The class is '.json_encode($class));

        return $class::loadResourceIntelligence($this, $this->getFieldValue($field), $field);
    }

    public function inputFields(): array
    {
        info('Getting Input Fields this should return an array currently is empty');
        return [];
    }

    public function inputField($key)
    {
        return $this->inputFields()[$key] ?? null;
    }
}
