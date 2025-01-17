<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SendEmailService;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Note;
use App\Models\StatusService;
use App\Models\StatusWarrantyService;

class ServiceController extends Controller
{
    public function show()
    {
        $customers = Customer::with('user')->get();
        $devices = Device::all();

        $statusWarrantyServices = StatusWarrantyService::all();
        $statusServices = StatusService::all();

        $services = Service::with('customer', 'customer.user', 'device', 'device.deviceType', 'statusWarrantyService', 'statusService')->get();

        return Inertia::render('Service/Services', [
            'services' => $services,
            'statusServices' => $statusServices,
            'statusWarrantyServices' => $statusWarrantyServices,
            'devices' => $devices,
            'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'device_id' => 'required|exists:devices,id',
            'status_warranty_service_id' => 'required|exists:status_warranty_services,id',
            'date_received' => 'date',
            'problem_description' => 'required|string|max:255',
            'estimated_completion' => 'date',
            'items_brought' => 'required|string|max:255',
            'status_service_id' => 'required|exists:status_services,id',
        ]);


        $service = Service::create([
            'service_code' => Str::upper(Str::random(6)),
            'customer_id' => $request->customer_id,
            'device_id' => $request->device_id,
            'status_warranty_service_id' => $request->status_warranty_service_id,
            'date_received' => $request->date_received,
            'problem_description' => $request->problem_description,
            'estimated_completion' => $request->estimated_completion,
            'items_brought' => $request->items_brought,
            'status_service_id' => $request->status_service_id,
        ]);

        $printService = route('service.print', $service->service_code);

        return Redirect::back()->with([
            'printService' => $printService
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:services,id',
            'customer_id' => 'required|exists:customers,id',
            'device_id' => 'required|exists:devices,id',
            'status_warranty_service_id' => 'required|exists:status_warranty_services,id',
            'date_received' => 'date',
            'problem_description' => 'required|string|max:255',
            'estimated_completion' => 'date',
            'items_brought' => 'required|string|max:255',
            'status_service_id' => 'required|exists:status_services,id',
        ]);

        $service = Service::findOrFail($request->id);

        $service->update([
            'customer_id' => $request->customer_id,
            'device_id' => $request->device_id,
            'status_warranty_service_id' => $request->status_warranty_service_id,
            'date_received' => $request->date_received,
            'problem_description' => $request->problem_description,
            'estimated_completion' => $request->estimated_completion,
            'items_brought' => $request->items_brought,
            'status_service_id' => $request->status_service_id,
        ]);

        return Redirect::back();
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:services,id',
        ]);

        $service = Service::findOrFail($request->id);
        $service->delete();

        return Redirect::back();
    }

    public function print($service_code)
    {
        $service = Service::where('service_code', $service_code)
            ->with('customer', 'customer.user', 'device', 'device.deviceType', 'statusWarrantyService', 'statusService')
            ->firstOrFail();

        $notes = Note::all();

        $data = [
            'title' => $service->service_code,
            'body' => 'Rincian',
            'email' => $service->customer->user->email,
            // Data
            'service' => $service,
            'notes' => $notes,
            'host_url' => url('/'),
        ];

        try {
            // Send the email
            Mail::to($data['email'])->send(new SendEmailService($data));
        } catch (\Exception $e) {
            // Log the error message or take any action you need
            Log::error('Failed to send email: ' . $e->getMessage());

            // You can also set a flash message or some indication for the user
            session()->flash('error', 'Gagal mengirim email, silakan coba lagi nanti.');
        }

        return inertia('Service/ServicePrint', [
            'service' => $service
        ]);
    }
}
