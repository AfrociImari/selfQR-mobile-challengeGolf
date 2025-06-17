<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Inertia\Inertia;
use App\Models\Admission;
use Illuminate\Support\Facades\Crypt;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $admissions = Admission::get();

        return response()->json($admissions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     /**
     * Update the qrCode value to corresponding checkinId
     */
    public function updateQrCode(Request $request)
    {
        $admission = Admission::where('admission_day', $request->admissionDay)
        ->where('admission_time', $request->admissionTime)
        ->where('box_name', $request->boxName)
        ->first();

        if (!$admission) {
            return response()->json(['message' => 'Admission not found'], 404);
        }
        
        $admission->billing_flg = true;
        $admission->save();
    }

    //generate encrypted url for mobile devices
    public function generateEncryptedMobileUrl(Request $request)
    {
        $request->validate([
            'self_qr_data' => 'required|string'
        ]);

        //divide the qrCode data to substring with delimiter '&'
        $data = explode('&', $request->input('self_qr_data'));

        if (count($data) !== 8) {
            return response()->json(['error' => 'Invalid QR Code format'], 400);
        }

        //2025-06-03&09:27:00&25&A0001&y&nj&0:1
        $admission_day = $data[0];
        $admission_time = $data[1];
        $box_name = $data[2];
        $customer_id = $data[3];
        $customer_class = $data[4];

        $encryptedAdmissionDay = Crypt::encryptString($admission_day);
        $encryptedAdmissionTime = Crypt::encryptString($admission_time);
        $encryptedboxName = Crypt::encryptString($box_name);

         $admission = Admission::where('admission_day', $admission_day)
        ->where('admission_time', $admission_time)
        ->where('box_name',  $box_name)
        ->first();

        if (!$admission) {
            $admission = new Admission([
                'admission_day' => $admission_day,
                'admission_time' => $admission_time,
                'box_name' => $box_name,
                'customer_id' => $customer_id,
                'customer_class' => $customer_class,
            ]);

            $admission->save();
        }
        
        $url = url("/admission/mobile/{$encryptedAdmissionDay}/{$encryptedAdmissionTime}/{$encryptedboxName}");

        return response()->json(['url' => $url]);
    }

    //Render the menu page on Mobile Device
    public function gotoMobileMenu($encrypted_admission_day,$encrypted_admission_time,$encrypted_boxName)
    {
        try {
            $admission_day = Crypt::decryptString($encrypted_admission_day);
            $admission_time = Crypt::decryptString($encrypted_admission_time);
            $box_name = Crypt::decryptString($encrypted_boxName);
        } catch (\Exception $e) {
            return redirect()->route('qrCodeError'); // Redirect if decryption fails
        }

         $admission = Admission::where('admission_day', $admission_day)
        ->where('admission_time', $admission_time)
        ->where('box_name',  $box_name)
        ->firstOrFail();

        //billing_flgがtrueの場合はQRコード無効画面を表示する
        if ($admission->billing_flg == 1) {
            return redirect()->route('qrCodeError');
        }

        return Inertia::render('MobileApp/MobileReceipt', [
            'checkin' => $admission
        ]);

    }

}
