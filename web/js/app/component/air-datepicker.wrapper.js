/**
 * Author: haiflive
 * require libs: datepicker, inputmask
 */
Vue.component('air-datepicker', {
  props: ['value', 'dateFormat', 'dateInputMask'],
  // template: '#niceSelect-template',
  mounted: function () {
    var vm = this
    
    $(this.$el)
      // init select2
      .datepicker({
        dateFormat: this.dateFormat,
        onSelect: function(fd, d, picker) {
          $(vm.$el).val(fd).trigger('change');
        },
        nextText: "", prevText: "", changeMonth: true, changeYear: true,
      })
      .inputmask(vm.dateInputMask)
      .val(this.value)
      .trigger('change')
      .on('change', function () {
        vm.$emit('input', this.value)
      });
    
    $(this.$el).val(vm.value).trigger('change');
  },
  watch: {
    value: function (value) {
      // update value
      $(this.$el).val(value).trigger('change');
    },
    
  },
  destroyed: function () {
    $(this.$el).datepicker().data('datepicker').destroy();
  },
  template: '<input type="text">'
})
