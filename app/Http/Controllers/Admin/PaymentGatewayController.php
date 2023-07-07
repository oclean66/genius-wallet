<?php
namespace App\Http\Controllers\Admin;
use Datatables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::paginate(16);
        return view('admin.payment.index',compact('gateways'));
    }

    public function create()
    {
        return view('admin.payment.create');
    }

    public function store(Request $request)
    {
        $request->validate( ['title' => 'unique:payment_gateways','subtitle'=>'required','fixed_charge'=>'required|gte:0','percent_charge'=>'required|gte:0','currency_id'=>'required','currency_id.*'=>'required']);
       
        $data = new PaymentGateway();
        $data->title = $request->title;
        $data->name = $request->title;
        $data->subtitle = $request->subtitle;
        $data->fixed_charge = $request->fixed_charge;
        $data->percent_charge = $request->percent_charge;
        $data->details = clean($request->details);
        $data->type = "manual";
        $data->keyword = "manual";
        $data->currency_id =  $request->currency_id;

        $data->save();
        return back()->with('success','New payment gateway added');
        
    }

    public function edit(PaymentGateway $paymentgateway)
    {
        return view('admin.payment.edit', compact('paymentgateway'));
    }

    //*** POST Request
    public function update(Request $request, PaymentGateway $gateway)
    {
        $data = $gateway;
        $prev = '';
        if ($data->type == "automatic")
        {
            //--- Validation Section
            $request->validate(['name' => 'unique:payment_gateways,name,' . $gateway->id, 'currency_id' => 'required']);

            $input = $request->except('_token');
            $info_data = $input['pkey'];

            if ($data->keyword == 'mollie')
            {
                $paydata = $data->convertAutoData();
                $prev = $paydata['key'];
            }

            if (array_key_exists("sandbox_check", $info_data))
            {
                $info_data['sandbox_check'] = 1;
            }
            else
            {
                if (strpos($data->information, 'sandbox_check') !== false)
                {
                    $info_data['sandbox_check'] = 0;
                    $text = $info_data['text'];
                    unset($info_data['text']);
                    $info_data['text'] = $text;
                }
            }
            $input['information'] = json_encode($info_data);
            $input['currency_id'] = $request->currency_id;
            $input['status'] = $request->status;
            $input['details'] = clean($request->details);
            $data->update($input);

            if ($data->keyword == 'mollie')
            {
                $paydata = $data->convertAutoData();
                $this->setEnv('MOLLIE_KEY', $paydata['key'], $prev);
            }

        }
        else
        {
            $rules = ['title' => 'unique:payment_gateways,title,' . $gateway->id,'fixed_charge'=>'required|gte:0','percent_charge'=>'required|gte:0'];
            $request->validate($rules);
            

            $input = $request->all();

            $input['currency_id'] = $request->currency_id;
            $input['status'] = $request->status;
            $data->update($input);
        }
      
        return back()->with('success','Payment gateway updated');

    }

    public function status($id,$status)
    {
        $user = PaymentGateway::findOrFail($id);
        $user->checkout = $status;
        $user->update();

        $mgs = __('Data Update Successfully');
        return response()->json($mgs);
    }


    public function destroy(PaymentGateway $paymentgateway)
    {
        $data = $paymentgateway;
        if($data->type == 'manual' || $data->keyword != null){
            $data->delete();
        }
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
    }
}

