# Laravel hiring test

## Create a Laravel APIs application for a travel agency.
### Glossary
<p> <b>Travel</b> is the main unit of the project: it contains all the necessary information, like the number of days, the images, title, etc. An example is Japan: road to Wonder or Norway: the land of the ICE;
</p>
<p> <b>Tour</b> is a specific dates-range of a travel with its own price and details. Japan: road to Wonder may have a tour from 10 to 27 May at €1899, another one from 10 to 15 September at €669 etc. At the end, you will book a tour, not a travel.
</p>

### Goals
<p>At the end, the project should have:</p>
<ul>
<li>A private (admin) endpoint to create new users. If you want, this could also be an artisan command, as you like. It will mainly be used to generate users for this exercise;</li>
<li>A private (admin) endpoint to create new travels;</li>
<li>A private (admin) endpoint to create new travels;</li>
<li>A private (admin) endpoint to create new tours for a travel;</li>
<li>A private (editor) endpoint to update a travel;</li>
<li>A public (no auth) endpoint to get a list of paginated travels. It must return only `public` travels;</li>
<li>A public (no auth) endpoint to get a list of paginated tours by the travel `slug` (e.g. all the tours of the travel `foo-bar`). Users can filter (search) the results by `priceFrom`, `priceTo`, `dateFrom` (from that `startingDate`) and `dateTo` (until that `startingDate`). User can sort the list by `price` asc and desc. They will **always** be sorted, after every additional user-provided filter, by ``startingDate`` asc.
    </li>
    </ul>

![image](https://github.com/hareom284/laravelmentorshiptest/assets/64596861/7e001cb0-870d-42bb-8173-efcb4fc134e3)


### Notes
<p>Feel  free to use the native Laravel authentication.</p>
<p>We use UUIDs as primary keys instead of incremental IDs, but it's not required for you to use them, although highly appreciated;</p>
<p> **Tours prices** are integer multiplied by 100: for example, €999 euro will be `99900`, but, when returned to Frontends, they will be formatted (`99900` / `100`);</p>
<p>**Tours names** inside the `samples` are a kind-of what we use internally, but you can use whatever you want;
<P>Every admin user will also have the `editor` role;</p>
<p>Every creation endpoint, of course, should create one and only one resource. You can't, for example, send an array of resource to create;</p>
<p>Usage of php-cs-fixer and larastan are a plus;</p>
<p>Creating docs is big plus;</p>
<p>
Feature tests are a big big plus.
</p>
