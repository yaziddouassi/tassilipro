import { usePage } from '@inertiajs/vue3'
import { TassiliListing } from '@/Vendor/TassiliLibs/stores/tassiliListing'
import { TassiliRoutes } from '@/Vendor/TassiliLibs/stores/tassiliRoutes'
import {TassiliInput}    from '@/Vendor/TassiliLibs/stores/tassiliInput'

export function listingService() {
  

  function setSettings() {
   const page = usePage()
   const tassiliroutes  = TassiliRoutes();
   const tassililisting = TassiliListing();
   const tassiliInput = TassiliInput()

   tassiliroutes.setRoutes(page.props.routes,page.props.tassiliPanel)
   tassililisting.groupActions = page.props.groupActions
   tassililisting.allFilters = page.props.allFilters
   tassililisting.customFilters = page.props.customFilters
   tassililisting.sessionFilter = page.props.sessionFilter
   tassililisting.show = false 
   tassiliInput.tassiliFormList = page.props.tassiliFormList
  }

 

  return {
    setSettings
  }
}