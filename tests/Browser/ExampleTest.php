<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testUiLoad()//test that user is able to see the heading in browser on going to the url
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('SERVER LIST');
        });
    }
    
    public function testDropdownContents()//test that in the browser, the dropdown values to filter HDD types are correct
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSelectHasOptions('#categoryFilter', ['SAS','SATA2']);
        });
    }
    
    public function testTableVisibility()//test that the table is visible on page load
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertVisible('#filterTable');
        });
    }
    
}
