# Laravel hiring test
## Create a Laravel APIs application for a travel agency.
### Glossary
- `Travel` is the main unit of the project: it contains all the necessary information, like the number of days, the images, the title, etc. An example is Japan: the road to Wonder or Norway: the land of the ICE;

- `Tour` is a specific dates-range of travel with its own price and details. Japan: the road to Wonder may have a tour from 10 to 27 May at €1899, another one from 10 to 15 September at €669, etc. In the end, you will book a tour, not a travel.


### Goals
<p>At the end, the project should have:</p>

- [x] A private (admin) endpoint to create new users. If you want, this could also be an artisan command, as you like. It will mainly be used to generate users for this exercise
- [x] A private (admin) endpoint to create new travels;
- [x] A private (admin) endpoint to create new tours for travel;
- [ ] A private (editor) endpoint to update travel;
- [x] A public (no auth) endpoint to get a list of paginated travels. It must return only `public` travels;
A public (no auth) endpoint to get a list of paginated tours by the travel `slug` (e.g. all the tours of the travel `foo-bar`). Users can filter (search) the results by `priceFrom`, `priceTo`, `dateFrom` (from that `startingDate`), and `dateTo` (until that `startingDate`). Users can sort the list by `price` asc and desc. They will **always** be sorted, after every additional user-provided filter, by ``starting date `` asc.

![image](https://github.com/hareom284/laravelmentorshiptest/assets/64596861/ede8c376-e9b2-4e33-a719-18b98871a924)


### Notes

<p>Feel  free to use the native Laravel authentication.</p>

- [x] We use UUIDs as primary keys instead of incremental IDs, but it's not required for you to use them, although highly appreciated;

- [x] `Tours prices` are integer multiplied by 100: for example, €999 euro will be `99900`, but, when returned to Frontends, they will be formatted ( `99900` / `100`);

- [ ] `Tours names` inside the `samples` are a kind of what we use internally, but you can use whatever you want;

- [x] Every admin user will also have the `editor` role;

- [ ] Every creation endpoint, of course, should create one and only one resource. You can't, for example, send an array of resources to create;

- [ ] Usage of php-cs-fixer and larastan are a plus;

- [ ] Creating docs is a big plus;

- [ ] Feature tests are a big big plus.

