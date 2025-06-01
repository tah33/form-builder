<?php

namespace App\Service;

use App\Models\Form;
use App\Models\FormConfiguration;

class FormService
{
    public function index()
    {
        //query with eagerloaded data
        return Form::with('configs')->withCount('configs')->latest()->paginate(10);
    }

    /**
     * @throws \Exception
     */
    protected function formatRequest($data)
    {
        if (data_get($data, 'file')) {
            //check if user has uploaded file: if yes then parse into associative array
            $contents   = file_get_contents(data_get($data, 'file'));
            $json_data  = json_decode($contents, true);
        } else {
            // parsing into associative array
            $json_data  = json_decode(data_get($data, 'json_data'), true);
        }
        // checking if the mandatory field was missing
        if (!$json_data || !data_get($json_data, 'title') || !data_get($json_data, 'action')) {
            throw new \Exception('Invalid data. Please check the sample file.');
        }
        $data['title']  = data_get($json_data, 'title');
        $data['action'] = data_get($json_data, 'action');
        $data['method'] = data_get($json_data, 'method');

        $fields = [];

        // rearranging fields for inserting into database
        foreach (data_get($json_data, 'fields') as $key=> $field) {
            if (count($field) === 0) {
                continue;
            }
            if (!data_get($field, 'name')) {
                throw new \Exception('Invalid data. Please check the sample file.');
            }
            $fields[] = [
                'type'          => data_get($field,'type', 'text'),
                'name'          => $field['name'],
                'label'         => data_get($field,'label', strtoupper($field['name'])),
                'placeholder'   => data_get($field,'placeholder', 'Enter the '. strtoupper($field['name'])),
                'required'      => data_get($field,'required', false),
                'order'         => $key+1,
            ];
        }
        $data['fields'] = $fields;
        return $data;
    }
    protected function deleteFieldsFromDB($id)
    {
        return FormConfiguration::where('form_id', $id)->delete();
    }
    public function store(array $data)
    {
        $data = $this->formatRequest($data);
        $form = Form::create($data);
        $form->configs()->createMany($data['fields']);
        return $form;
    }

    public function find($id)
    {
        return Form::find($id);
    }

    public function update(array $data, $id)
    {
        //find, parse request, deleting old ones and inserting fields into db new one
        $form = $this->find($id);
        $data = $this->formatRequest($data);
        $this->deleteFieldsFromDB($id);
        $form->configs()->createMany($data['fields']);
        return $form->update($data);
    }

    public function destroy($id): int
    {
        return Form::destroy($id);
    }

    public function fieldsByForm($id)
    {
        return FormConfiguration::where('form_id', $id)->orderBy('order')->get();
    }
}
