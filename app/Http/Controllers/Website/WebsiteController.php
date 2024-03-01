<?php>
namespace App\Http\Controllers\Website;

use App\Models\Faq;
use App\Models\Seo;
use App\Models\Plan;
use App\Models\Post;
use AmrShawky\Currency;
use App\Models\Feature;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Traits\PaymentAble;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class WebsiteController extends Controller
{
    use SEOToolsTrait, PaymentAble;

    public function home()
    {
        $content = metaContent('home') ?? (object) [
            'title' => 'Default Home Title',
            'description' => 'Default Home Description',
            'keywords' => 'default, keywords',
        ];
        
        $this->setSEOData($content);

        $faqs = Faq::all();
        $features = Feature::all();
        $testimonials = Testimonial::all();
        $plans = Plan::with('planFeatures')->whereStatus(1)->get();

        return view('website.home', compact('faqs', 'features', 'testimonials', 'plans'));
    }

    public function about()
    {
        $content = metaContent('about') ?? (object) [
            'title' => 'Default About Title',
            'description' => 'Default About Description',
            'keywords' => 'default, keywords',
        ];
        
        $this->setSEOData($content);

        $testimonials = Testimonial::all();

        return view('website.about', compact('testimonials'));
    }

    // Autres méthodes du contrôleur...

    private function setSEOData($content)
    {
        $this->seo()->setTitle($content->title);
        $this->seo()->setDescription($content->description);
        SEOMeta::setKeywords($content->keywords);
        $this->seo()->opengraph()->setUrl(url()->current());
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->twitter()->setSite(url()->current());
        $this->seo()->jsonLd()->setType('Website');
    }
}
