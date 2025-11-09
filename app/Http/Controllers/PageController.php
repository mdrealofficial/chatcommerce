<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\MessengerService;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    protected $facebook;
    protected $messengerService;

    public function __construct(MessengerService $messengerService)
    {
        $this->messengerService = $messengerService;
        
        try {
            $this->facebook = new Facebook([
                'app_id' => config('services.facebook.app_id'),
                'app_secret' => config('services.facebook.app_secret'),
                'default_graph_version' => config('services.facebook.graph_version'),
            ]);
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK initialization error: ' . $e->getMessage());
        }
    }

    public function connect()
    {
        $page = Page::where('user_id', auth()->id())->first();
        
        return view('pages.connect', compact('page'));
    }

    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()->route('pages.connect')
                ->with('error', 'Facebook authorization failed. Please try again.');
        }

        try {
            $helper = $this->facebook->getRedirectLoginHelper();
            $accessToken = $helper->getAccessToken();

            if (!$accessToken) {
                throw new \Exception('Unable to get access token');
            }

            // Get user's pages
            $response = $this->facebook->get('/me/accounts', $accessToken);
            $pages = $response->getDecodedBody()['data'] ?? [];

            if (empty($pages)) {
                return redirect()->route('pages.connect')
                    ->with('error', 'No pages found. Please make sure you are a page admin.');
            }

            // For simplicity, connect the first page
            // In production, you might want to let user choose which page to connect
            $pageData = $pages[0];
            $pageAccessToken = $pageData['access_token'];
            $pageId = $pageData['id'];

            // Exchange for long-lived token
            $oAuth2Client = $this->facebook->getOAuth2Client();
            $longLivedToken = $oAuth2Client->getLongLivedAccessToken($pageAccessToken);

            // Get page details
            $pageResponse = $this->facebook->get("/{$pageId}?fields=name,picture", $longLivedToken);
            $pageInfo = $pageResponse->getDecodedBody();

            // Subscribe to webhooks
            $this->messengerService->subscribeToPage($longLivedToken->getValue(), $pageId);

            // Store page information
            Page::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'page_id' => $pageId,
                    'page_access_token' => $longLivedToken->getValue(),
                    'page_name' => $pageInfo['name'],
                    'page_profile_image' => $pageInfo['picture']['data']['url'] ?? null,
                    'is_connected' => true,
                    'token_expires_at' => now()->addDays(60),
                ]
            );

            return redirect()->route('pages.connect')
                ->with('success', 'Facebook page connected successfully!');

        } catch (\Exception $e) {
            Log::error('Facebook page connection error: ' . $e->getMessage());
            
            return redirect()->route('pages.connect')
                ->with('error', 'Failed to connect Facebook page. Please try again.');
        }
    }

    public function disconnect(Request $request)
    {
        $page = Page::where('user_id', auth()->id())->first();

        if ($page) {
            $page->update(['is_connected' => false]);
        }

        return redirect()->route('pages.connect')
            ->with('success', 'Facebook page disconnected successfully.');
    }

    public function getLoginUrl()
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        
        $permissions = [
            'pages_messaging',
            'pages_show_list',
            'pages_manage_metadata',
            'pages_manage_engagement',
            'pages_read_engagement',
        ];

        return $helper->getLoginUrl(route('pages.callback'), $permissions);
    }
}
