<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\Faq;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\Style;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('site.home', [
            'page' => CmsPage::query()->where('slug', 'home')->first(),
            'styles' => Style::query()->where('status', 'active')->limit(6)->get(),
            'quotes' => Testimonial::query()->where('status', 'published')->limit(3)->get(),
        ]);
    }

    public function page(string $slug): View
    {
        $page = CmsPage::query()->where('slug', $slug)->firstOrFail();

        return view('site.page', ['page' => $page]);
    }

    public function products(Request $request): View
    {
        $styles = Style::query()
            ->where('status', 'active')
            ->when($request->string('route')->toString(), fn ($q, $route) => $q->where('production_route', $route))
            ->when($request->string('q')->toString(), fn ($q, $term) => $q->where('name', 'like', '%'.$term.'%'))
            ->orderBy('name')
            ->get();

        return view('site.products', ['styles' => $styles]);
    }

    public function product(Style $style): View
    {
        return view('site.product', ['style' => $style]);
    }

    public function contact(): View
    {
        return view('site.contact');
    }

    public function faq(): View
    {
        return view('site.faq', ['faqs' => Faq::query()->orderBy('sort')->get()]);
    }

    public function careers(): View
    {
        return view('site.careers', ['jobs' => JobOpening::query()->where('status', 'open')->get()]);
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'job_opening_id' => ['required', 'exists:job_openings,id'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:40'],
        ]);
        JobApplication::query()->create($data);

        return back()->with('status', 'Application received. HR will call you.');
    }

    public function legal(string $slug): View
    {
        $copy = [
            'privacy' => 'We keep buyer, worker and supplier records to run the mill. We do not sell them. Bank and national-id fields are encrypted. Access follows the role.',
            'terms' => 'Quotations are valid until the date printed on them. A purchase order is confirmed only after merchandising accepts it in the system. Yarn shade is approved against the sealed lab dip.',
        ];

        return view('site.page', ['page' => (object) ['title' => ucfirst($slug), 'body' => $copy[$slug] ?? '']]);
    }

    public function sitemap()
    {
        $urls = ['/', '/about', '/capabilities', '/sustainability', '/quality', '/sampling', '/products', '/contact', '/faq', '/careers'];
        foreach (Style::query()->where('status', 'active')->pluck('slug') as $slug) {
            $urls[] = '/products/'.$slug;
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $url) {
            $xml .= '<url><loc>'.e(url($url)).'</loc></url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots()
    {
        return response("User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain']);
    }

    public function llms()
    {
        $lines = ["# Knitwear Factory", '', 'Narayanganj mill. Sweaters on flat bed, polos on circular knit.', ''];
        foreach (Style::query()->where('status', 'active')->get() as $style) {
            $lines[] = '- '.$style->name.' ('.$style->gauge.', '.$style->production_route.'): '.url('/products/'.$style->slug);
        }

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function locale(string $locale)
    {
        session(['locale' => $locale]);
        app()->setLocale($locale);

        return back();
    }
}
