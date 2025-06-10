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
        $admission_no = $request->admission_no;
        $admission = Admission::where('admission_no', $admission_no)->first();

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

        //A20250602001&2025-06-03&09:27:00&25&A0001&y&nj&0:1
        $admission_no = $data[0];
        $admission_day = $data[1];
        $admission_time = $data[2];
        $box_name = $data[3];
        $customer_id = $data[4];
        $customer_class = $data[5];

        $encryptedAdmissionNo = Crypt::encryptString($admission_no);

        $admission = Admission::where('admission_no', $admission_no)->first();

        if (!$admission) {
            $admission = new Admission([
                'admission_no' => $admission_no,
                'admission_day' => $admission_day,
                'admission_time' => $admission_time,
                'box_name' => $box_name,
                'customer_id' => $customer_id,
                'customer_class' => $customer_class,
                'qr_code' => $encryptedAdmissionNo,
            ]);

            $admission->save();
        }
        
        $url = url("/admission/mobile/{$encryptedAdmissionNo}");

        return response()->json(['url' => $url]);
    }

    //Render the menu page on Mobile Device
    public function gotoMobileMenu($encrypted_admission_no)
    {
        try {
            $admission_no = Crypt::decryptString($encrypted_admission_no);
        } catch (\Exception $e) {
            return redirect()->route('qrCodeError'); // Redirect if decryption fails
        }

        $admission = Admission::where('admission_no', $admission_no)->firstOrFail();

        //billing_flgがtrueの場合はQRコード無効画面を表示する
        if ($admission->billing_flg == 1) {
            return redirect()->route('qrCodeError');
        }

        return Inertia::render('MobileApp/MobileReceipt', [
            'checkin' => $admission
        ]);

    }

}
