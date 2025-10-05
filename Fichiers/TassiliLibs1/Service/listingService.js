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

   tassiliroutes.setRoutes(page.props.tassiliSettings.routes,page.props.tassiliSettings.tassiliPanel)
   tassililisting.groupActions = page.props.tassiliSettings.groupActions
   tassililisting.allFilters = page.props.tassiliSettings.allFilters
   tassililisting.customFilters = page.props.tassiliSettings.customFilters
   tassililisting.sessionFilter = page.props.tassiliSettings.sessionFilter
   tassililisting.show = false 
   tassiliInput.tassiliFormList = page.props.tassiliSettings.tassiliFormList
  }

 

  return {
    setSettings
  }
}