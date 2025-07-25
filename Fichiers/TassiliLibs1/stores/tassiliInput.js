import { defineStore } from 'pinia'

export const TassiliInput = defineStore('counter', {
  state: () => ({
    form : {},
    formStart : {},
    formInfo : {},
    wizardInfo : {},
    wizardCurrent : 1,
    errors: {}, 
    tassiliFormList : {},
  }),

  actions: {
    setError(err) {
      this.errors = err
    },

    resetError() {
      this.errors = {}
    },


  }



})