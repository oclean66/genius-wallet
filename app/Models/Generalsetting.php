<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generalsetting extends Model
{
    use HasFactory;
    public $timestamps = false;
      /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $casts = ['cookie'=>'object']; 
    protected $fillable = ['is_admin_loader', 'header_logo','footer_logo','favicon', 'website_loader','dashboard_loader','breadcumb','title', 'smtp_host','smtp_port','smtp_user','mail_encryption','smtp_pass','from_email','mail_type','from_name','error_banner','theme_color','is_debug','is_disqus','disqus','is_tawk','tawk_id','is_verify','is_cookie','cookie_btn_text','cookie_text','is_popup','popup_image','popup_title','popup_url','home_page_section','maintenance','menu','allowed_email','contact_no' ];

    

} 
