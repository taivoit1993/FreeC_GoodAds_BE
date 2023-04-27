<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
    public function listingAccount($customerId){
        $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves all child accounts of the manager specified in search
        // calls below.
        $query = 'SELECT customer_client.client_customer, customer_client.level,'
            . ' customer_client.manager, customer_client.descriptive_name,'
            . ' customer_client.currency_code, customer_client.time_zone,'
            . ' customer_client.id FROM customer_client WHERE customer_client.level <= 1';

        $stream = $googleAdsServiceClient->searchStream(
            $customerId,
            $query
        );

        foreach ($stream->iterateAllElements() as $googleAdsRow) {
            /** @var GoogleAdsRow $googleAdsRow */
            $customerClient = $googleAdsRow->getCustomerClient();
            dd($customerClient->getId());
//            // For all level-1 (direct child) accounts that are a manager account, the above
//            // query will be run against them to create an associative array of managers to
//            // their child accounts for printing the hierarchy afterwards.
//            $customerIdsToChildAccounts[$customerIdToSearch][] = $customerClient;
//            // Checks if the child account is a manager itself so that it can later be processed
//            // and added to the map if it hasn't been already.
//            if ($customerClient->getManager()) {
//                // A customer can be managed by multiple managers, so to prevent visiting
//                // the same customer multiple times, we need to check if it's already in the
//                // map.
//                $alreadyVisited = array_key_exists(
//                    $customerClient->getId(),
//                    $customerIdsToChildAccounts
//                );
//                if (!$alreadyVisited && $customerClient->getLevel() === 1) {
//                    array_push($managerCustomerIdsToSearch, $customerClient->getId());
//                }
//            }
        }

//        $rootCustomerClient = null;
//        // Adds the root customer ID to the list of IDs to be processed.
//        $managerCustomerIdsToSearch = [$rootCustomerId];
//
//        // Performs a breadth-first search algorithm to build an associative array mapping
//        // managers to their child accounts ($customerIdsToChildAccounts).
//        $customerIdsToChildAccounts = [];
//
//        while (!empty($managerCustomerIdsToSearch)) {
//            $customerIdToSearch = array_shift($managerCustomerIdsToSearch);
//            // Issues a search request by specifying page size.
//            /** @var GoogleAdsServerStreamDecorator $stream */
//            $stream = $googleAdsServiceClient->searchStream(
//                $customerIdToSearch,
//                $query
//            );
//
//            // Iterates over all elements to get all customer clients under the specified customer's
//            // hierarchy.
//            foreach ($stream->iterateAllElements() as $googleAdsRow) {
//                /** @var GoogleAdsRow $googleAdsRow */
//                $customerClient = $googleAdsRow->getCustomerClient();
//
//                // Gets the CustomerClient object for the root customer in the tree.
//                if ($customerClient->getId() === $rootCustomerId) {
//                    $rootCustomerClient = $customerClient;
//                }
//
//                // The steps below map parent and children accounts. Continue here so that managers
//                // accounts exclude themselves from the list of their children accounts.
//                if ($customerClient->getId() === $customerIdToSearch) {
//                    continue;
//                }
//
//                // For all level-1 (direct child) accounts that are a manager account, the above
//                // query will be run against them to create an associative array of managers to
//                // their child accounts for printing the hierarchy afterwards.
//                $customerIdsToChildAccounts[$customerIdToSearch][] = $customerClient;
//                // Checks if the child account is a manager itself so that it can later be processed
//                // and added to the map if it hasn't been already.
//                if ($customerClient->getManager()) {
//                    // A customer can be managed by multiple managers, so to prevent visiting
//                    // the same customer multiple times, we need to check if it's already in the
//                    // map.
//                    $alreadyVisited = array_key_exists(
//                        $customerClient->getId(),
//                        $customerIdsToChildAccounts
//                    );
//                    if (!$alreadyVisited && $customerClient->getLevel() === 1) {
//                        array_push($managerCustomerIdsToSearch, $customerClient->getId());
//                    }
//                }
//            }
//        }
//
//        return is_null($rootCustomerClient) ? null
//            : [$rootCustomerClient->getId() => $customerIdsToChildAccounts];
    }


}
