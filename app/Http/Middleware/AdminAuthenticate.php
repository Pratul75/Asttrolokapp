<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\SidebarController;
use App\Mixins\Financial\MultiCurrency;
use App\User;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() and auth()->user()->isAdmin()) {

            if (auth()->user()->hasPermission('admin_notifications_list')) {
                $adminUser = User::find(1);

                \Session::forget('impersonated');

                $unreadNotifications = $adminUser->getUnReadNotifications();

                view()->share('unreadNotifications', $unreadNotifications);
            }

            $generalSettings = getGeneralSettings();
            view()->share('generalSettings', $generalSettings);


            $userLanguages = $this->getUserLanguagesLists($generalSettings);

            view()->share('userLanguages', $userLanguages);

            $currency = currencySign();
            view()->share('currency', $currency);

            if (getFinancialCurrencySettings('multi_currency')) {
                $multiCurrency = new MultiCurrency();
                $currencies = $multiCurrency->getCurrencies();

                if ($currencies->isNotEmpty()) {
                    view()->share('currencies', $currencies);
                }
            }

            $user = auth()->user();
            view()->share('authUser', $user);

            $sidebarController = new SidebarController();

            $sidebarBeeps = [];
            $sidebarBeeps['courses'] = $sidebarController->getCoursesBeep();
            $sidebarBeeps['bundles'] = $sidebarController->getBundlesBeep();
            $sidebarBeeps['webinars'] = $sidebarController->getWebinarsBeep();
            $sidebarBeeps['textLessons'] = $sidebarController->getTextLessonsBeep();
            $sidebarBeeps['reviews'] = $sidebarController->getReviewsBeep();
            $sidebarBeeps['classesComments'] = $sidebarController->getClassesCommentsBeep();
            $sidebarBeeps['bundleComments'] = $sidebarController->getBundleCommentsBeep();
            $sidebarBeeps['blogComments'] = $sidebarController->getBlogCommentsBeep();
            $sidebarBeeps['productComments'] = $sidebarController->getProductCommentsBeep();
            $sidebarBeeps['payoutRequest'] = $sidebarController->getPayoutRequestBeep();
            $sidebarBeeps['offlinePayments'] = $sidebarController->getOfflinePaymentsBeep();

            view()->share('sidebarBeeps', $sidebarBeeps);


            // locale config
            if (!Session::has('locale')) {
                Session::put('locale', mb_strtolower(getDefaultLocale()));
            }
            App::setLocale(session('locale'));
             $this->redirectIfPublic();
            return $next($request);
        }

        return redirect(getAdminPanelUrl().'/login');
    }

    public function getUserLanguagesLists($generalSettings)
    {
        $userLanguages = ($generalSettings and !empty($generalSettings['user_languages'])) ? $generalSettings['user_languages'] : null;

        if (!empty($userLanguages) and is_array($userLanguages)) {
            $userLanguages = getLanguages($userLanguages);
        } else {
            $userLanguages = [];
        }

        if (count($userLanguages) > 0) {
            $site_language = $generalSettings['site_language'] ?? app()->getLocale();

            foreach ($userLanguages as $locale => $language) {
                if (mb_strtolower($locale) == mb_strtolower($site_language)) {
                    $firstKey = array_key_first($userLanguages);

                    if ($firstKey != $locale) {
                        $firstValue = $userLanguages[$firstKey];

                        unset($userLanguages[$locale]);
                        unset($userLanguages[$firstKey]);

                        $userLanguages = array_merge([
                            $locale => $language,
                            $firstKey => $firstValue
                        ], $userLanguages);
                    }
                }
            }
        }

        return $userLanguages;
    }
     function redirectIfPublic() {
   
    $requestUri = $_SERVER['REQUEST_URI'];
    
    if (strpos($requestUri, '/public/') === 0) {
        $newUri = str_replace('/public/', '/', $requestUri, $count);
        if ($count > 0) {
            $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $currentUrl = $scheme . '://' . $host . $newUri;
            header("Location: $currentUrl");
            exit();
        }
    }
}
}
