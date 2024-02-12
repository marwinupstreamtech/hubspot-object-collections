# HubSpot CRM object collection

A Laravel package can help to collect the HubSpot CRM objects properties.

## Inspiration

Here are a few examples of well-documented code:

* [HubSpot](https://developers.hubspot.com/docs/api/crm/companies)
## Usage & formatting

### Using an OAUTH 
$hubSpotObjectCollection = new HubSpotObjectCollection($api_token);
 
### Using the Private Application
Set the API token to your .env file HUBSPOT_PRIVATE_APP_API_TOKEN

$hubSpotObjectCollection = new HubSpotObjectCollection();

```
Project Name: HubSpot Object Collection
Description: To collect the HubSpot CRM objects[companies, contacts, deals, feedback_submissions, line_items, products, quotes, discounts, fees, taxes, tickets, goals] and return only the properties.

### Dependencies
- php ^8.1
- Guzzle ^7.4

## Demo
/**
 * The private API key set in the .env file
 */
$sourceSdk = new HubSpotObjectCollection();
$contacts = $sourceSdk->getCollection('contacts', ['hs_object_id', 'email']);

## Organization

* Upstreamtech/JackTaylorGroup
