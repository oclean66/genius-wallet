<?php

namespace App\Traits;

trait ContentRules {
   
    public $commonRules = [
        'title'              => 'required',
        'heading'            => 'required',
        'sub_heading'        => 'required'
    ];

    public function banner()
    {
        return [
            'title'         => 'required',
            'heading'       => 'required',
            'sub_heading'   => 'required',
            'button_1_name' => 'required',
            'button_1_url'  => 'required',
            'button_2_name' => 'required',
            'button_2_url'  => 'required',
            'image'         => 'image|mimes:jpg,jpeg,png|max:2048',
            'image_size'    => 'required_with:image'
        ];
    }

    public function about()
    {
        return [
            'title'              => 'required',
            'heading'            => 'required',
            'short_details'      => 'required',
            'feature_1_icon'     => 'required',
            'feature_1_title'    => 'required',
            'feature_1_details'  => 'required',
            'feature_2_icon'     => 'required',
            'feature_2_title'    => 'required',
            'feature_2_details'  => 'required',
            'image'              => 'image|mimes:jpg,jpeg,png|max:2048',
            'image_size'         => 'required_with:image'
        ];
    }

    public function service()
    {
        return $this->commonRules;
    }
    public function service_subcontent()
    {
        return [
            'icon'               => 'required',
            'title'              => 'required',
            'details'            => 'required'
        ];
    }

    public function how()
    {
        return [
            'title'              => 'required',
            'heading'            => 'required',
            'sub_heading'        => 'required',
            'image'              => 'image|mimes:jpg,jpeg,png|max:2048',
            'image_size'         => 'required_with:image'
        ];
    }

    public function how_subcontent()
    {
        return [
            'icon'               => 'required',
            'title'              => 'required',
            'details'            => 'required'
        ];
    }
    public function counter()
    {
        return [
            'title'              => 'required',
            'heading'            => 'required',
            'sub_heading'        => 'required',
            'button_name'        => 'required',
            'button_url'         => 'required',
        ];
    }

    public function counter_subcontent()
    {
        return [
            'icon'               => 'required',
            'title'              => 'required',
            'counter'            => 'required'
        ];
    }

    public function feature()
    {
         return $this->commonRules;
    }
    public function feature_subcontent()
    {
        return [
            'image'              => 'image|mimes:jpg,jpeg,png,PNG|max:2048',
            'image_size'         => 'required_with:image',
            'title'              => 'required',
            'details'            => 'required'
        ];
    }

    public function faq()
    {
        return $this->commonRules;
    }

    public function faq_subcontent()
    {
        return [
            'question'           => 'required',
            'answer'             => 'required'
        ];
    }

    public function testimonial()
    {
        return $this->commonRules;
    }

    public function testimonial_subcontent()
    {
        return [
            'image'              => 'image|mimes:jpg,jpeg,png,PNG|max:2048',
            'image_size'         => 'required_with:image',
            'name'               => 'required',
            'quote'              => 'required'
        ];
    }

    public function blog()
    {
         return $this->commonRules;
    }

    public function sponsors()
    {
        return $this->commonRules;
    }

    public function sponsors_subcontent()
    {
        return [
            'image'              => 'image|mimes:jpg,jpeg,png,PNG|max:2048',
            'image_size'         => 'required_with:image'
        ];
    }
    public function social_subcontent()
    {
        return [
            'icon'               => 'required',
            'url'                => 'required',
        ];
    }
    public function policies_subcontent()
    {
        return [
            'lang'               => 'required',
            'title'              => 'required',
            'description'        => 'required',
        ];
    }

    public function contact()
    {
        return [
            'title'              => 'required',
            'heading'            => 'required',
            'sub_heading'        => 'required',
            'phone'              => 'required',
            'email'              => 'required|email',
            'address'            => 'required'
       ];
    }

}
