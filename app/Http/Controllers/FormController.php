<?php

namespace App\Http\Controllers;

use App\Http\Requests\Form\CustomFormRequest;
use App\Http\Resources\FormResource;
use App\Models\FormConfiguration;
use App\Service\FormService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class FormController extends Controller
{
    public function __construct(protected FormService $formService)
    {
    }
    public function index()
    {
        try {
            $forms                  = $this->formService->index();
            $data                   = [
                'forms'             => FormResource::collection($forms), //using collection for better data efficience
                'created'           => session()->has('status'),
                'paginated_data'    => [ //return the customize paginated data
                    'rows'          => $forms->total(),
                    'total'         => $forms->total(),
                    'currentPage'   => $forms->currentPage(),
                    'perPage'       => $forms->perPage(),
                    'last_page'     => $forms->lastPage(),
                    'next_page_url' => $forms->nextPageUrl(),
                    'first_item'    => $forms->firstItem(),
                ],
            ];
            if (session()->has('status')) {
                $created_data = [
                    'status'    => 200,
                    'message'   => session()->has('status'),
                ];
                $data = array_merge($data, $created_data);
            }
            return inertia()->render('Admin/Form/Index', $data);
        } catch (\Exception $e) {
            return back()->withErrors([
                'status'    => $e->getCode() ? : 500,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function store(CustomFormRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->formService->store($request->all());
            DB::commit();
            return redirect()->route('forms.index')->with('status', 'Form successfully added!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'status'    => $e->getCode() ? : 500,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function show($id): Response|RedirectResponse
    {
        try {
            $data           = [
                'form'      => $this->formService->find($id),
                'fields'    => $this->formService->fieldsByForm($id),
                'success'   => true,
            ];
            return inertia()->render('Admin/Form/Show', $data);

        } catch (\Exception $e) {
            return back()->withErrors([
                'status'    => $e->getCode() ? : 500,
                'message'   => $e->getMessage(),
            ]);
        }
    }
    public function edit($id)
    {
        try {
            $data                   = [
                'form'              => $this->formService->find($id),
                'success'           => true,
            ];
            return inertia()->render('Admin/Form/Index', $data);

        } catch (\Exception $e) {
            return back()->withErrors([
                'status'    => $e->getCode() ? : 500,
                'message'   => $e->getMessage(),
            ]);
        }
    }
    public function update(FormRequest $request, $id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->formService->update($request->all(), $id);
            DB::commit();
            return redirect()->route('forms.index')->with('status', 'Form successfully updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'status'    => $e->getCode() ? : 500,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->formService->destroy($id);
            return redirect()->route('forms.index');
        } catch (\Exception $e) {
            return back()->withErrors([
                'status'    => $e->getCode() ? : 500,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function configForm(Request $request): RedirectResponse
    {
        //filtering 0 key index as there will be no chance to have an iem with 0 ID
        $orders = array_filter($request->data, function ($value, $key) {
            return $key > 0;
        }, ARRAY_FILTER_USE_BOTH);

        //updating form orders after drag n drop
        foreach ($orders as $id => $order) {
            FormConfiguration::where('id', $id)->update(['order' => $order]);
        }
        return back()->with('status', 'Form successfully updated!');
    }
}
