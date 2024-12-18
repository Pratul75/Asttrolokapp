<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mixins\Installment\InstallmentPlans;
use App\Models\AdvertisingBanner;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\FeatureWebinar;
use App\Models\HomePageStatistic;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\Role;
use App\Models\Sale;
use App\Models\SpecialOffer;
use App\Models\Subscribe;
use App\Models\Ticket;
use App\Models\TrendCategory;
use App\Models\UpcomingCourse;
use App\Models\Webinar;
use App\Models\Remedy;
use App\Models\Testimonial;
use App\Models\Setting;
use App\Models\Category;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HomeSlider;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{
    public function index()
    {
        //  print_r($request->getPathInfo());
        // if ($request->getPathInfo() === '/home/index.php') {
            
        //     return redirect('/');
        // }
        // $JobsController = new JobsController();
        // $JobsController->sendInstallmentReminders();
   
        $homeSections = HomeSection::orderBy('order', 'asc')->get();
        $selectedSectionsName = $homeSections->pluck('name')->toArray();

        $featureWebinars = null;
        if (in_array(HomeSection::$featured_classes, $selectedSectionsName)) {
            $featureWebinars = FeatureWebinar::whereIn('page', ['home', 'home_categories'])
                ->where('status', 'publish')
                ->whereHas('webinar', function ($query) {
                    $query->where('status', Webinar::$active);
                })
                ->with([
                    'webinar' => function ($query) {
                        $query->with([
                            'teacher' => function ($qu) {
                                $qu->select('id', 'full_name', 'avatar');
                            },
                            'reviews' => function ($query) {
                                $query->where('status', 'active');
                            },
                            'tickets',
                            'feature'
                        ]);
                    }
                ])
                ->orderBy('updated_at', 'desc')
                ->get();
            //$selectedWebinarIds = $featureWebinars->pluck('id')->toArray();
        }

        if (in_array(HomeSection::$latest_classes, $selectedSectionsName)) {
            $latestWebinars = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->orderBy('updated_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'tickets',
                    'feature'
                ])
                ->limit(5)
                ->get();

            //$selectedWebinarIds = array_merge($selectedWebinarIds, $latestWebinars->pluck('id')->toArray());
        }

       

        if (in_array(HomeSection::$upcoming_courses, $selectedSectionsName)) {
            $upcomingCourses = UpcomingCourse::where('status', Webinar::$active)
                ->orderBy('created_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    }
                ])
                ->limit(6)
                ->get();
        }

        if (in_array(HomeSection::$best_sellers, $selectedSectionsName)) {
            $bestSaleWebinarsIds = Sale::whereNotNull('webinar_id')
                ->select(DB::raw('COUNT(id) as cnt,webinar_id'))
                ->groupBy('webinar_id')
                ->orderBy('cnt', 'DESC')
                ->limit(6)
                ->pluck('webinar_id')
                ->toArray();

            $bestSaleWebinars = Webinar::whereIn('id', $bestSaleWebinarsIds)
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'sales',
                    'tickets',
                    'feature'
                ])
                ->get();

            //$selectedWebinarIds = array_merge($selectedWebinarIds, $bestSaleWebinars->pluck('id')->toArray());
        }

        if (in_array(HomeSection::$best_rates, $selectedSectionsName)) {
            $bestRateWebinars = Webinar::join('webinar_reviews', 'webinars.id', '=', 'webinar_reviews.webinar_id')
                ->select('webinars.*', 'webinar_reviews.rates', 'webinar_reviews.status', DB::raw('avg(rates) as avg_rates'))
                ->where('webinars.status', 'active')
                ->where('webinars.private', false)
                ->where('webinar_reviews.status', 'active')
                ->groupBy('teacher_id')
                ->orderBy('avg_rates', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    }
                ])
                ->limit(6)
                ->get();
        }

        // hasDiscountWebinars
        if (in_array(HomeSection::$discount_classes, $selectedSectionsName)) {
            $now = time();
            $webinarIdsHasDiscount = [];

            $tickets = Ticket::where('start_date', '<', $now)
                ->where('end_date', '>', $now)
                ->get();

            foreach ($tickets as $ticket) {
                if ($ticket->isValid()) {
                    $webinarIdsHasDiscount[] = $ticket->webinar_id;
                }
            }

            $specialOffersWebinarIds = SpecialOffer::where('status', 'active')
                ->where('from_date', '<', $now)
                ->where('to_date', '>', $now)
                ->pluck('webinar_id')
                ->toArray();

            $webinarIdsHasDiscount = array_merge($specialOffersWebinarIds, $webinarIdsHasDiscount);

            $hasDiscountWebinars = Webinar::whereIn('id', array_unique($webinarIdsHasDiscount))
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'sales',
                    'tickets',
                    'feature'
                ])
                ->limit(6)
                ->get();
        }
        
        // .\ hasDiscountWebinars
 if (in_array(HomeSection::$hindi_classes, $selectedSectionsName)) {
    
            $hindiWebinars = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) {
                    $query->where('lang','HI');
                })
                
                // ->orderBy('updated_at', 'desc')
                ->orderBy('order')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'tickets',
                    'feature'
                ])
                ->limit(6)
                ->get();
                // print_r($hindiWebinars);
        }
        
        $agent = new Agent();
                if ($agent->isMobile()){
                   if (in_array(HomeSection::$english_classes, $selectedSectionsName)) {
            $englishclasses = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) {
                    $query->where('lang','EN');
                })
                ->orderBy('updated_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'tickets',
                ])
                ->limit(6)
                ->get();
                // print_r($englishclasses);
        }
            }else{
                if (in_array(HomeSection::$english_classes, $selectedSectionsName)) {
            $englishclasses = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) {
                    $query->where('lang','EN');
                })
                ->orderBy('updated_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'tickets',
                ])
                ->limit(3)
                ->get();
                // print_r($englishclasses);
        }
            }
            
  
        
        //  if (in_array(HomeSection::$latest_bundles, $selectedSectionsName)) {
        //     $latestBundles = Bundle::where('status', Webinar::$active)
        //         ->orderBy('updated_at', 'desc')
        //         ->with([
        //             'teacher' => function ($qu) {
        //                 $qu->select('id', 'full_name', 'avatar');
        //             },
        //             'reviews' => function ($query) {
        //                 $query->where('status', 'active');
        //             },
        //             'tickets',
        //         ])
        //         ->limit(6)
        //         ->get();
        // }
        if (in_array(HomeSection::$free_classes, $selectedSectionsName)) {
            $freeWebinars = Webinar::where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) {
                    $query->whereNull('price')
                        ->orWhere('price', '0');
                })
                ->orderBy('updated_at', 'desc')
                ->with([
                    'teacher' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                    'reviews' => function ($query) {
                        $query->where('status', 'active');
                    },
                    'tickets',
                    'feature'
                ])
                ->limit(6)
                ->get();
        }
        
if (in_array(HomeSection::$remedies, $selectedSectionsName)) {
            $remedies = Remedy::where('remedies.type', "remedy")
            ->limit(4)
                ->get();
        }

        if (in_array(HomeSection::$store_products, $selectedSectionsName)) {
            $newProducts = Product::where('status', Product::$active)
                ->orderBy('updated_at', 'desc')
                ->with([
                    'creator' => function ($qu) {
                        $qu->select('id', 'full_name', 'avatar');
                    },
                ])
                ->limit(6)
                ->get();
        }

        if (in_array(HomeSection::$trend_categories, $selectedSectionsName)) {
            $trendCategories = TrendCategory::with([
                'category' => function ($query) {
                    $query->withCount([
                        'webinars' => function ($query) {
                            $query->where('status', 'active');
                        }
                    ]);
                }
            ])->orderBy('created_at', 'desc')
                ->get();
        }

        if (in_array(HomeSection::$blog, $selectedSectionsName)) {
            $blog = Blog::where('status', 'publish')
                ->with(['category', 'author' => function ($query) {
                    $query->select('id', 'full_name');
                }])->orderBy('updated_at', 'desc')
                ->withCount('comments')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
        }
// print_r($blog);die();
        if (in_array(HomeSection::$instructors, $selectedSectionsName)) {
            $instructors = User::where('role_name', Role::$teacher)
                ->select('id', 'full_name', 'avatar', 'bio')
                ->where('status', 'active')
                ->where('consultant', '0')
                ->where(function ($query) {
                    $query->where('ban', false)
                        ->orWhere(function ($query) {
                            $query->whereNotNull('ban_end_at')
                                ->where('ban_end_at', '<', time());
                        });
                })
                ->limit(8)
                ->get();
        }


        if (in_array(HomeSection::$consultant, $selectedSectionsName)) {
            $consultant = User::where('role_name', Role::$teacher)
                ->select('id', 'full_name', 'avatar','rating', 'bio')
                ->where('status', 'active')
                ->where('consultant', '1')
                ->where(function ($query) {
                    $query->where('ban', false)
                        ->orWhere(function ($query) {
                            $query->whereNotNull('ban_end_at')
                                ->where('ban_end_at', '<', time());
                        });
                })
                ->inRandomOrder()->limit(8)
                ->get();
        }

        if (in_array(HomeSection::$organizations, $selectedSectionsName)) {
            $organizations = User::where('role_name', Role::$organization)
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->where('ban', false)
                        ->orWhere(function ($query) {
                            $query->whereNotNull('ban_end_at')
                                ->where('ban_end_at', '<', time());
                        });
                })
                ->withCount('webinars')
                ->orderBy('webinars_count', 'desc')
                ->limit(6)
                ->get();
        }
        
        if (in_array(HomeSection::$testimonials, $selectedSectionsName)) {
            $testimonials = Testimonial::where('status', 'active')->get();
        }

        $sidebanner = Setting::getsidebanner();
        
        if (in_array(HomeSection::$subscribes, $selectedSectionsName)) {
            $subscribes = Subscribe::all();

            $user = auth()->user();
            $installmentPlans = new InstallmentPlans($user);

            foreach ($subscribes as $subscribe) {
                if (getInstallmentsSettings('status') and (empty($user) or $user->enable_installments) and $subscribe->price > 0) {
                    $installments = $installmentPlans->getPlans('subscription_packages', $subscribe->id);

                    $subscribe->has_installment = (!empty($installments) and count($installments));
                }
            }
        }

        if (in_array(HomeSection::$find_instructors, $selectedSectionsName)) {
            $findInstructorSection = getFindInstructorsSettings();
        }

        if (in_array(HomeSection::$reward_program, $selectedSectionsName)) {
            $rewardProgramSection = getRewardProgramSettings();
        }


        if (in_array(HomeSection::$become_instructor, $selectedSectionsName)) {
            $becomeInstructorSection = getBecomeInstructorSectionSettings();
        }


        if (in_array(HomeSection::$forum_section, $selectedSectionsName)) {
            $forumSection = getForumSectionSettings();
        }

        $advertisingBanners = AdvertisingBanner::where('published', true)
            ->whereIn('position', ['home1', 'home2'])
            ->get();


        $siteGeneralSettings = getGeneralSettings();
        $heroSection = (!empty($siteGeneralSettings['hero_section2']) and $siteGeneralSettings['hero_section2'] == "1") ? "2" : "1";
        $heroSectionData = getHomeHeroSettings($heroSection);

        if (in_array(HomeSection::$video_or_image_section, $selectedSectionsName)) {
            $boxVideoOrImage = getHomeVideoOrImageBoxSettings();
        }

        $seoSettings = getSeoMetas('home');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.home_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.home_title');
        $pageRobot = getPageRobot('home');

        $statisticsSettings = getStatisticsSettings();

        $homeDefaultStatistics = null;
        $homeCustomStatistics = null;

        if (!empty($statisticsSettings['enable_statistics'])) {
            if (!empty($statisticsSettings['display_default_statistics'])) {
                $homeDefaultStatistics = $this->getHomeDefaultStatistics();
            } else {
                $homeCustomStatistics = HomePageStatistic::query()->orderBy('order', 'asc')->limit(5)->get();
            }
        }
$categories123 = Category::where('parent_id', null)
->orderBy('order', 'asc')
            ->get();
        


        $HomeSlider =HomeSlider::limit(5)->get();
        
        $testimonial_video = Setting::gettestimonialVideo();
        
        
        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'heroSection' => $heroSection,
            'HomeSlider' => $HomeSlider,
            'heroSectionData' => $heroSectionData,
            'homeSections' => $homeSections,
            'featureWebinars' => $featureWebinars,
            'latestWebinars' => $latestWebinars ?? [],
            'latestBundles' => $latestBundles ?? [],
            'upcomingCourses' => $upcomingCourses ?? [],
            'bestSaleWebinars' => $bestSaleWebinars ?? [],
            'hasDiscountWebinars' => $hasDiscountWebinars ?? [],
            'bestRateWebinars' => $bestRateWebinars ?? [],
            'freeWebinars' => $freeWebinars ?? [],
            'hindiWebinars' => $hindiWebinars ?? [],
            'englishclasses' => $englishclasses ?? [],
            'newProducts' => $newProducts ?? [],
            'trendCategories' => $trendCategories ?? [],
            'instructors' => $instructors ?? [],
            'consultant' => $consultant  ?? [],
            'testimonials' => $testimonials ?? [],
            'subscribes' => $subscribes ?? [],
            'blog' => $blog ?? [],
            'organizations' => $organizations ?? [],
            'remedies' => $remedies ?? [],
            'advertisingBanners1' => $advertisingBanners->where('position', 'home1'),
            'advertisingBanners2' => $advertisingBanners->where('position', 'home2'),
            'homeDefaultStatistics' => $homeDefaultStatistics,
            'homeCustomStatistics' => $homeCustomStatistics,
            'boxVideoOrImage' => $boxVideoOrImage ?? null,
            'findInstructorSection' => $findInstructorSection ?? null,
            'rewardProgramSection' => $rewardProgramSection ?? null,
            'becomeInstructorSection' => $becomeInstructorSection ?? null,
            'forumSection' => $forumSection ?? null,
            'cate123'=>$categories123,
            'sidebanner'=>$sidebanner,
            'testimonial_video' => $testimonial_video,
        ];

        $agent = new Agent();
        if ($agent->isMobile()){
                return view(getTemplate() . '.pages.home', $data);
        }else{
            return view('web.default2' . '.pages.home', $data);
        }
        // return view(getTemplate() . '.pages.home', $data);
    }
      public function checkvideo()
    {
        // return redirect('/');
        return view('web.default' . '.pages.checkvideo');
    }
    
    public function redirect(Request $request)
    {
        // return redirect('/');
         return response()->json(["status"=>true] ,200);
    }

    private function getHomeDefaultStatistics()
    {
        $skillfulTeachersCount = User::where('role_name', Role::$teacher)
            ->where(function ($query) {
                $query->where('ban', false)
                    ->orWhere(function ($query) {
                        $query->whereNotNull('ban_end_at')
                            ->where('ban_end_at', '<', time());
                    });
            })
            ->where('status', 'active')
            ->count();

        $studentsCount = User::where('role_name', Role::$user)
            ->where(function ($query) {
                $query->where('ban', false)
                    ->orWhere(function ($query) {
                        $query->whereNotNull('ban_end_at')
                            ->where('ban_end_at', '<', time());
                    });
            })
            ->where('status', 'active')
            ->count();

        $liveClassCount = Webinar::where('type', 'webinar')
            ->where('status', 'active')
            ->count();

        $offlineCourseCount = Webinar::where('status', 'active')
            ->whereIn('type', ['course', 'text_lesson'])
            ->count();

        return [
            'skillfulTeachersCount' => $skillfulTeachersCount,
            'studentsCount' => $studentsCount,
            'liveClassCount' => $liveClassCount,
            'offlineCourseCount' => $offlineCourseCount,
        ];
    }
    
    public function thankyou(Request $request)
    {
         $agent = new Agent();
        if ($agent->isMobile()){
            return view(getTemplate() . '.pages.thankyou');
        }else{
            return view('web.default2' . '.pages.thankyou');
        }
        //  return view('web.default.pages.thankyou');
    }
    
    public function about(Request $request)
    {
         $agent = new Agent();
        if ($agent->isMobile()){
            return view(getTemplate() . '.pages.aboutus');
        }else{
            return view('web.default2' . '.pages.aboutus');
        }
        //  return view('web.default.pages.aboutus');
    }
     public function webhookdata(Request $request)
    {
        
        date_default_timezone_set('Asia/Kolkata');
       
      $webhookurl = 'https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjUwNTZiMDYzZjA0MzU1MjZkNTUzMzUxMzci_pc';
        // Collection object
        $webhookdata = [
          'name' => $request->name,
          'mobile' => $request->mobile,
          'email' => $request->email,
           'create_at' => date("Y/m/d H:i")
        ];
        // Initializes a new cURL session
        $webhookcurl = curl_init($webhookurl);
        // Set the CURLOPT_RETURNTRANSFER option to true
        curl_setopt($webhookcurl, CURLOPT_RETURNTRANSFER, true);
        // Set the CURLOPT_POST option to true for POST request
        curl_setopt($webhookcurl, CURLOPT_POST, true);
        // Set the request data as JSON using json_encode function
        curl_setopt($webhookcurl, CURLOPT_POSTFIELDS,  json_encode($webhookdata));
        // Set custom headers for RapidAPI Auth and Content-Type header
        
        // Execute cURL request with all previous settings
        $webhookresponse = curl_exec($webhookcurl);
        // Close cURL session
        curl_close($webhookcurl);
        // print_r($request->all());die;
        return true;
    }
}
