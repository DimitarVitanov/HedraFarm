<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyInfo;

class CompanyInfoController extends Controller
{
    public function getCompanyInfo()
    {
        $company = CompanyInfo::first();
        $company->show_instagram = $company->show_instagram ? 'true' : 'false';
        $company->show_facebook = $company->show_facebook ? 'true' : 'false';
        $company->show_linkedin = $company->show_linkedin ? 'true' : 'false';
        return response()->json(['success' => true, 'data' => $company, 'message' => 'Company info returned successfully']);
    }

    public function store(Request $request)
    {
        $company_info = null;
        if($request->id && $request->id != 'null'){
            $company_info = CompanyInfo::whereId($request->id)->first();
        }
        if ($company_info) {
            if(isset($request->name)) $company_info->name = $request->name;
            if(isset($request->address)) $company_info->address = $request->address;
            if(isset($request->phone)) $company_info->phone = $request->phone;
            if(isset($request->email)) $company_info->email = $request->email;
            if(isset($request->worktime)) $company_info->worktime = $request->worktime;
            if(isset($request->quote)) $company_info->quote = $request->quote;
            if(isset($request->instagram_acc_link)) $company_info->instagram_acc_link = $request->instagram_acc_link;
            if(isset($request->facebook_acc_link)) $company_info->facebook_acc_link = $request->facebook_acc_link;
            if(isset($request->linkedin_acc_link)) $company_info->linkedin_acc_link = $request->linkedin_acc_link;
            if(isset($request->show_instagram)) $company_info->show_instagram = $request->show_instagram == 'true' ? 1 : 0;
            if(isset($request->show_facebook)) $company_info->show_facebook = $request->show_facebook == 'true' ? 1 : 0;
            if(isset($request->show_linkedin)) $company_info->show_linkedin = $request->show_linkedin == 'true' ? 1 : 0;
            $company_info->save();
            return response()->json(['success' => true, 'message' => 'Company info updated successfully']);
        }
            $company_info = new CompanyInfo();
            if(isset($request->name)) $company_info->name = $request->name;
            if(isset($request->address)) $company_info->address = $request->address;
            if(isset($request->phone)) $company_info->phone = $request->phone;
            if(isset($request->email)) $company_info->email = $request->email;
            if(isset($request->worktime)) $company_info->worktime = $request->worktime;
            if(isset($request->quote)) $company_info->quote = $request->quote;
            if(isset($request->instagram_acc_link)) $company_info->instagram_acc_link = $request->instagram_acc_link;
            if(isset($request->facebook_acc_link)) $company_info->facebook_acc_link = $request->facebook_acc_link;
            if(isset($request->linkedin_acc_link)) $company_info->linkedin_acc_link = $request->linkedin_acc_link;
            if(isset($request->show_instagram)) $company_info->show_instagram = $request->show_instagram;
            if(isset($request->show_facebook)) $company_info->show_facebook = $request->show_facebook;
            if(isset($request->show_linkedin)) $company_info->show_linkedin = $request->show_linkedin;
            $company_info->save();
            return response()->json(['success' => true, 'message' => 'Company info added successfully']);

    }
}
