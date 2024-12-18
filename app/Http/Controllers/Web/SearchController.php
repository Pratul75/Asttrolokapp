<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Role;
use App\Models\Webinar;
use App\Models\Remedy;
use App\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
class SearchController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $this->validate($request, [
            'search' => 'required|string|max:20',
        ]);

        $search = $request->get('search', null);

        if (!empty($search) and strlen($search) >= 3) {
            $webinars = Webinar::where('status', 'active')
                ->where('private', false)
                ->whereTranslationLike('title', "%$search%")
                ->with([
                    'teacher' => function ($query) {
                        $query->select('id', 'full_name', 'avatar');
                    },
                    'reviews'
                ])
                ->get();
                
            $remedies = Remedy::where('status', 'active')
                ->where('private', false)
                ->whereTranslationLike('title', "%$search%")
                ->get();

            $products = Product::where('status', 'active')
                ->whereTranslationLike('title', "%$search%")
                ->with([
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'avatar');
                    }
                ])
                ->get();

            $users = User::where('status', 'active')
                ->where('full_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%")
                ->with([
                    'webinars' => function ($query) {
                        $query->where('status', 'active');
                        //dd(getBindedSQL($query));
                    }
                ])
                ->get();

            $teachers = $users->where('role_name', Role::$teacher);
            $organizations = $users->where('role_name', Role::$organization);

            $seoSettings = getSeoMetas('search');
            $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.search_page_title');
            $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.search_page_title');
            $pageRobot = getPageRobot('search');

            $data = [
                'pageTitle' => $pageTitle,
                'pageDescription' => $pageDescription,
                'pageRobot' => $pageRobot,
                'resultCount' => count($webinars) + count($teachers) + count($organizations),
                'webinars' => $webinars,
                'remedies' => $remedies,
                'teachers' => $teachers,
                'organizations' => $organizations,
                'products' => $products,
            ];
        }
$agent = new Agent();
                if ($agent->isMobile()){
                    return view(getTemplate() . '.pages.search', $data);
            }else{
                return view('web.default2' . '.pages.search', $data);
            }
        // return view(getTemplate() . '.pages.search', $data);
    }
}
