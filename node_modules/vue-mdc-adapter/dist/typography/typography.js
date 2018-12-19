/**
* @module vue-mdc-adaptertypography 0.18.2
* @exports VueMDCTypography
* @copyright (c) 2017-present, Sebastien Tasson
* @license https://opensource.org/licenses/MIT
* @implements {"@material/tabs":"0.38.0","material-components-web":"0.38.2"}
* @requires {"vue":"^2.5.6"}
* @see https://github.com/stasson/vue-mdc-adapter
*/

(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global.VueMDCTypography = factory());
}(this, (function () { 'use strict';

    function autoInit(plugin) {
      // Auto-install
      var _Vue = null;
      if (typeof window !== 'undefined') {
        _Vue = window.Vue;
      } else if (typeof global !== 'undefined') {
        /*global global*/
        _Vue = global.Vue;
      }
      if (_Vue) {
        _Vue.use(plugin);
      }
    }

    function BasePlugin(components) {
      return {
        version: '0.18.2',
        install: function install(vm) {
          for (var key in components) {
            var component = components[key];
            vm.component(component.name, component);
          }
        },
        components: components
      };
    }

    var defineProperty = function (obj, key, value) {
      if (key in obj) {
        Object.defineProperty(obj, key, {
          value: value,
          enumerable: true,
          configurable: true,
          writable: true
        });
      } else {
        obj[key] = value;
      }

      return obj;
    };

    /* global CustomEvent */

    var scope = Math.floor(Math.random() * Math.floor(0x10000000)).toString() + '-';

    var typos = ['headline1', 'headline2', 'headline3', 'headline4', 'headline5', 'headline6', 'subtitle1', 'subtitle2', 'body1', 'body2', 'caption', 'button', 'overline'];

    var mdcTypoMixin = function mdcTypoMixin(name) {
      return {
        render: function render(createElement) {
          var _class;

          return createElement(this.tag, {
            class: (_class = {
              'mdc-typo': true
            }, defineProperty(_class, name, true), defineProperty(_class, 'mdc-typography--' + this.typo, true), _class),
            attrs: this.$attrs,
            on: this.$listeners
          }, this.$slots.default);
        }
      };
    };

    function mdcTypoPropMixin(defaultTag, defaultTypo, validTypos) {
      return {
        props: {
          tag: {
            type: String,
            default: defaultTag
          },
          typo: {
            type: String,
            default: defaultTypo,
            validator: function validator(value) {
              return validTypos.indexOf(value) !== -1;
            }
          }
        }
      };
    }

    var mdcTextSection = {
      name: 'mdc-text-section',
      props: {
        tag: {
          type: String,
          default: 'section'
        }
      },
      render: function render(createElement) {
        return createElement(this.tag, {
          class: {
            'mdc-typography': true,
            'mdc-text-section': true
          },
          attrs: this.$attrs,
          on: this.$listeners
        }, this.$slots.default);
      }
    };

    var mdcText = {
      name: 'mdc-text',
      mixins: [mdcTypoMixin('mdc-text'), mdcTypoPropMixin('p', 'body1', typos)]
    };

    var mdcDisplay = {
      name: 'mdc-display',
      mixins: [mdcTypoMixin('mdc-display'), mdcTypoPropMixin('h1', 'headline4', ['headline4', 'headline3', 'headline2', 'headline1'])]
    };

    var mdcHeadline = {
      name: 'mdc-headline',
      mixins: [mdcTypoMixin('mdc-headline'), mdcTypoPropMixin('h2', 'headline5', ['headline5'])]
    };

    var mdcTitle = {
      name: 'mdc-title',
      mixins: [mdcTypoMixin('mdc-title'), mdcTypoPropMixin('h3', 'headline6', ['headline6'])]
    };

    var mdcSubHeading = {
      name: 'mdc-subheading',
      mixins: [mdcTypoMixin('mdc-subheading'), mdcTypoPropMixin('h4', 'subtitle2', ['subtitle1', 'subtitle2'])]
    };

    var mdcBody = {
      name: 'mdc-body',
      mixins: [mdcTypoMixin('mdc-body'), mdcTypoPropMixin('p', 'body1', ['body1', 'body2'])]
    };

    var mdcCaption = {
      name: 'mdc-caption',
      mixins: [mdcTypoMixin('mdc-caption'), mdcTypoPropMixin('span', 'caption', ['caption'])]
    };

    var plugin = BasePlugin({
      mdcTextSection: mdcTextSection,
      mdcText: mdcText,
      mdcBody: mdcBody,
      mdcCaption: mdcCaption,
      mdcDisplay: mdcDisplay,
      mdcHeadline: mdcHeadline,
      mdcSubHeading: mdcSubHeading,
      mdcTitle: mdcTitle
    });

    autoInit(plugin);

    return plugin;

})));
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoidHlwb2dyYXBoeS5qcyIsInNvdXJjZXMiOlsiLi4vLi4vY29tcG9uZW50cy9iYXNlL2F1dG8taW5pdC5qcyIsIi4uLy4uL2NvbXBvbmVudHMvYmFzZS9iYXNlLXBsdWdpbi5qcyIsIi4uLy4uL2NvbXBvbmVudHMvYmFzZS9jdXN0b20tZXZlbnQuanMiLCIuLi8uLi9jb21wb25lbnRzL2Jhc2UvdW5pcXVlaWQtbWl4aW4uanMiLCIuLi8uLi9jb21wb25lbnRzL3R5cG9ncmFwaHkvbWRjLXR5cG9ncmFwaHkuanMiLCIuLi8uLi9jb21wb25lbnRzL3R5cG9ncmFwaHkvaW5kZXguanMiLCIuLi8uLi9jb21wb25lbnRzL3R5cG9ncmFwaHkvZW50cnkuanMiXSwic291cmNlc0NvbnRlbnQiOlsiZXhwb3J0IGZ1bmN0aW9uIGF1dG9Jbml0KHBsdWdpbikge1xuICAvLyBBdXRvLWluc3RhbGxcbiAgbGV0IF9WdWUgPSBudWxsXG4gIGlmICh0eXBlb2Ygd2luZG93ICE9PSAndW5kZWZpbmVkJykge1xuICAgIF9WdWUgPSB3aW5kb3cuVnVlXG4gIH0gZWxzZSBpZiAodHlwZW9mIGdsb2JhbCAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAvKmdsb2JhbCBnbG9iYWwqL1xuICAgIF9WdWUgPSBnbG9iYWwuVnVlXG4gIH1cbiAgaWYgKF9WdWUpIHtcbiAgICBfVnVlLnVzZShwbHVnaW4pXG4gIH1cbn1cbiIsImV4cG9ydCBmdW5jdGlvbiBCYXNlUGx1Z2luKGNvbXBvbmVudHMpIHtcbiAgcmV0dXJuIHtcbiAgICB2ZXJzaW9uOiAnX19WRVJTSU9OX18nLFxuICAgIGluc3RhbGw6IHZtID0+IHtcbiAgICAgIGZvciAobGV0IGtleSBpbiBjb21wb25lbnRzKSB7XG4gICAgICAgIGxldCBjb21wb25lbnQgPSBjb21wb25lbnRzW2tleV1cbiAgICAgICAgdm0uY29tcG9uZW50KGNvbXBvbmVudC5uYW1lLCBjb21wb25lbnQpXG4gICAgICB9XG4gICAgfSxcbiAgICBjb21wb25lbnRzXG4gIH1cbn1cbiIsIi8qIGdsb2JhbCBDdXN0b21FdmVudCAqL1xuXG5leHBvcnQgZnVuY3Rpb24gZW1pdEN1c3RvbUV2ZW50KGVsLCBldnRUeXBlLCBldnREYXRhLCBzaG91bGRCdWJibGUgPSBmYWxzZSkge1xuICBsZXQgZXZ0XG4gIGlmICh0eXBlb2YgQ3VzdG9tRXZlbnQgPT09ICdmdW5jdGlvbicpIHtcbiAgICBldnQgPSBuZXcgQ3VzdG9tRXZlbnQoZXZ0VHlwZSwge1xuICAgICAgZGV0YWlsOiBldnREYXRhLFxuICAgICAgYnViYmxlczogc2hvdWxkQnViYmxlXG4gICAgfSlcbiAgfSBlbHNlIHtcbiAgICBldnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudCgnQ3VzdG9tRXZlbnQnKVxuICAgIGV2dC5pbml0Q3VzdG9tRXZlbnQoZXZ0VHlwZSwgc2hvdWxkQnViYmxlLCBmYWxzZSwgZXZ0RGF0YSlcbiAgfVxuICBlbC5kaXNwYXRjaEV2ZW50KGV2dClcbn1cbiIsImNvbnN0IHNjb3BlID1cbiAgTWF0aC5mbG9vcihNYXRoLnJhbmRvbSgpICogTWF0aC5mbG9vcigweDEwMDAwMDAwKSkudG9TdHJpbmcoKSArICctJ1xuXG5leHBvcnQgY29uc3QgVk1BVW5pcXVlSWRNaXhpbiA9IHtcbiAgYmVmb3JlQ3JlYXRlKCkge1xuICAgIHRoaXMudm1hX3VpZF8gPSBzY29wZSArIHRoaXMuX3VpZFxuICB9XG59XG4iLCJjb25zdCB0eXBvcyA9IFtcbiAgJ2hlYWRsaW5lMScsXG4gICdoZWFkbGluZTInLFxuICAnaGVhZGxpbmUzJyxcbiAgJ2hlYWRsaW5lNCcsXG4gICdoZWFkbGluZTUnLFxuICAnaGVhZGxpbmU2JyxcbiAgJ3N1YnRpdGxlMScsXG4gICdzdWJ0aXRsZTInLFxuICAnYm9keTEnLFxuICAnYm9keTInLFxuICAnY2FwdGlvbicsXG4gICdidXR0b24nLFxuICAnb3ZlcmxpbmUnXG5dXG5cbmV4cG9ydCBjb25zdCBtZGNUeXBvTWl4aW4gPSBuYW1lID0+IHtcbiAgcmV0dXJuIHtcbiAgICByZW5kZXIoY3JlYXRlRWxlbWVudCkge1xuICAgICAgcmV0dXJuIGNyZWF0ZUVsZW1lbnQoXG4gICAgICAgIHRoaXMudGFnLFxuICAgICAgICB7XG4gICAgICAgICAgY2xhc3M6IHtcbiAgICAgICAgICAgICdtZGMtdHlwbyc6IHRydWUsXG4gICAgICAgICAgICBbbmFtZV06IHRydWUsXG4gICAgICAgICAgICBbYG1kYy10eXBvZ3JhcGh5LS0ke3RoaXMudHlwb31gXTogdHJ1ZVxuICAgICAgICAgIH0sXG4gICAgICAgICAgYXR0cnM6IHRoaXMuJGF0dHJzLFxuICAgICAgICAgIG9uOiB0aGlzLiRsaXN0ZW5lcnNcbiAgICAgICAgfSxcbiAgICAgICAgdGhpcy4kc2xvdHMuZGVmYXVsdFxuICAgICAgKVxuICAgIH1cbiAgfVxufVxuXG5leHBvcnQgZnVuY3Rpb24gbWRjVHlwb1Byb3BNaXhpbihkZWZhdWx0VGFnLCBkZWZhdWx0VHlwbywgdmFsaWRUeXBvcykge1xuICByZXR1cm4ge1xuICAgIHByb3BzOiB7XG4gICAgICB0YWc6IHtcbiAgICAgICAgdHlwZTogU3RyaW5nLFxuICAgICAgICBkZWZhdWx0OiBkZWZhdWx0VGFnXG4gICAgICB9LFxuICAgICAgdHlwbzoge1xuICAgICAgICB0eXBlOiBTdHJpbmcsXG4gICAgICAgIGRlZmF1bHQ6IGRlZmF1bHRUeXBvLFxuICAgICAgICB2YWxpZGF0b3I6IHZhbHVlID0+IHZhbGlkVHlwb3MuaW5kZXhPZih2YWx1ZSkgIT09IC0xXG4gICAgICB9XG4gICAgfVxuICB9XG59XG5cbmV4cG9ydCBjb25zdCBtZGNUZXh0U2VjdGlvbiA9IHtcbiAgbmFtZTogJ21kYy10ZXh0LXNlY3Rpb24nLFxuICBwcm9wczoge1xuICAgIHRhZzoge1xuICAgICAgdHlwZTogU3RyaW5nLFxuICAgICAgZGVmYXVsdDogJ3NlY3Rpb24nXG4gICAgfVxuICB9LFxuICByZW5kZXIoY3JlYXRlRWxlbWVudCkge1xuICAgIHJldHVybiBjcmVhdGVFbGVtZW50KFxuICAgICAgdGhpcy50YWcsXG4gICAgICB7XG4gICAgICAgIGNsYXNzOiB7XG4gICAgICAgICAgJ21kYy10eXBvZ3JhcGh5JzogdHJ1ZSxcbiAgICAgICAgICAnbWRjLXRleHQtc2VjdGlvbic6IHRydWVcbiAgICAgICAgfSxcbiAgICAgICAgYXR0cnM6IHRoaXMuJGF0dHJzLFxuICAgICAgICBvbjogdGhpcy4kbGlzdGVuZXJzXG4gICAgICB9LFxuICAgICAgdGhpcy4kc2xvdHMuZGVmYXVsdFxuICAgIClcbiAgfVxufVxuXG5leHBvcnQgY29uc3QgbWRjVGV4dCA9IHtcbiAgbmFtZTogJ21kYy10ZXh0JyxcbiAgbWl4aW5zOiBbbWRjVHlwb01peGluKCdtZGMtdGV4dCcpLCBtZGNUeXBvUHJvcE1peGluKCdwJywgJ2JvZHkxJywgdHlwb3MpXVxufVxuXG5leHBvcnQgY29uc3QgbWRjRGlzcGxheSA9IHtcbiAgbmFtZTogJ21kYy1kaXNwbGF5JyxcbiAgbWl4aW5zOiBbXG4gICAgbWRjVHlwb01peGluKCdtZGMtZGlzcGxheScpLFxuICAgIG1kY1R5cG9Qcm9wTWl4aW4oJ2gxJywgJ2hlYWRsaW5lNCcsIFtcbiAgICAgICdoZWFkbGluZTQnLFxuICAgICAgJ2hlYWRsaW5lMycsXG4gICAgICAnaGVhZGxpbmUyJyxcbiAgICAgICdoZWFkbGluZTEnXG4gICAgXSlcbiAgXVxufVxuXG5leHBvcnQgY29uc3QgbWRjSGVhZGxpbmUgPSB7XG4gIG5hbWU6ICdtZGMtaGVhZGxpbmUnLFxuICBtaXhpbnM6IFtcbiAgICBtZGNUeXBvTWl4aW4oJ21kYy1oZWFkbGluZScpLFxuICAgIG1kY1R5cG9Qcm9wTWl4aW4oJ2gyJywgJ2hlYWRsaW5lNScsIFsnaGVhZGxpbmU1J10pXG4gIF1cbn1cblxuZXhwb3J0IGNvbnN0IG1kY1RpdGxlID0ge1xuICBuYW1lOiAnbWRjLXRpdGxlJyxcbiAgbWl4aW5zOiBbXG4gICAgbWRjVHlwb01peGluKCdtZGMtdGl0bGUnKSxcbiAgICBtZGNUeXBvUHJvcE1peGluKCdoMycsICdoZWFkbGluZTYnLCBbJ2hlYWRsaW5lNiddKVxuICBdXG59XG5cbmV4cG9ydCBjb25zdCBtZGNTdWJIZWFkaW5nID0ge1xuICBuYW1lOiAnbWRjLXN1YmhlYWRpbmcnLFxuICBtaXhpbnM6IFtcbiAgICBtZGNUeXBvTWl4aW4oJ21kYy1zdWJoZWFkaW5nJyksXG4gICAgbWRjVHlwb1Byb3BNaXhpbignaDQnLCAnc3VidGl0bGUyJywgWydzdWJ0aXRsZTEnLCAnc3VidGl0bGUyJ10pXG4gIF1cbn1cblxuZXhwb3J0IGNvbnN0IG1kY0JvZHkgPSB7XG4gIG5hbWU6ICdtZGMtYm9keScsXG4gIG1peGluczogW1xuICAgIG1kY1R5cG9NaXhpbignbWRjLWJvZHknKSxcbiAgICBtZGNUeXBvUHJvcE1peGluKCdwJywgJ2JvZHkxJywgWydib2R5MScsICdib2R5MiddKVxuICBdXG59XG5cbmV4cG9ydCBjb25zdCBtZGNDYXB0aW9uID0ge1xuICBuYW1lOiAnbWRjLWNhcHRpb24nLFxuICBtaXhpbnM6IFtcbiAgICBtZGNUeXBvTWl4aW4oJ21kYy1jYXB0aW9uJyksXG4gICAgbWRjVHlwb1Byb3BNaXhpbignc3BhbicsICdjYXB0aW9uJywgWydjYXB0aW9uJ10pXG4gIF1cbn1cbiIsImltcG9ydCB7IEJhc2VQbHVnaW4gfSBmcm9tICcuLi9iYXNlJ1xuaW1wb3J0IHtcbiAgbWRjVGV4dFNlY3Rpb24sXG4gIG1kY1RleHQsXG4gIG1kY0JvZHksXG4gIG1kY0NhcHRpb24sXG4gIG1kY0Rpc3BsYXksXG4gIG1kY0hlYWRsaW5lLFxuICBtZGNTdWJIZWFkaW5nLFxuICBtZGNUaXRsZVxufSBmcm9tICcuL21kYy10eXBvZ3JhcGh5LmpzJ1xuXG5leHBvcnQge1xuICBtZGNUZXh0U2VjdGlvbixcbiAgbWRjVGV4dCxcbiAgbWRjQm9keSxcbiAgbWRjQ2FwdGlvbixcbiAgbWRjRGlzcGxheSxcbiAgbWRjSGVhZGxpbmUsXG4gIG1kY1N1YkhlYWRpbmcsXG4gIG1kY1RpdGxlXG59XG5cbmV4cG9ydCBkZWZhdWx0IEJhc2VQbHVnaW4oe1xuICBtZGNUZXh0U2VjdGlvbixcbiAgbWRjVGV4dCxcbiAgbWRjQm9keSxcbiAgbWRjQ2FwdGlvbixcbiAgbWRjRGlzcGxheSxcbiAgbWRjSGVhZGxpbmUsXG4gIG1kY1N1YkhlYWRpbmcsXG4gIG1kY1RpdGxlXG59KVxuIiwiaW1wb3J0ICcuL3N0eWxlcy5zY3NzJ1xuaW1wb3J0IHsgYXV0b0luaXQgfSBmcm9tICcuLi9iYXNlJ1xuaW1wb3J0IHBsdWdpbiBmcm9tICcuL2luZGV4LmpzJ1xuZXhwb3J0IGRlZmF1bHQgcGx1Z2luXG5cbmF1dG9Jbml0KHBsdWdpbilcbiJdLCJuYW1lcyI6WyJhdXRvSW5pdCIsInBsdWdpbiIsIl9WdWUiLCJ3aW5kb3ciLCJWdWUiLCJnbG9iYWwiLCJ1c2UiLCJCYXNlUGx1Z2luIiwiY29tcG9uZW50cyIsInZlcnNpb24iLCJpbnN0YWxsIiwia2V5IiwiY29tcG9uZW50Iiwidm0iLCJuYW1lIiwic2NvcGUiLCJNYXRoIiwiZmxvb3IiLCJyYW5kb20iLCJ0b1N0cmluZyIsInR5cG9zIiwibWRjVHlwb01peGluIiwicmVuZGVyIiwiY3JlYXRlRWxlbWVudCIsInRhZyIsImNsYXNzIiwidHlwbyIsImF0dHJzIiwiJGF0dHJzIiwib24iLCIkbGlzdGVuZXJzIiwiJHNsb3RzIiwiZGVmYXVsdCIsIm1kY1R5cG9Qcm9wTWl4aW4iLCJkZWZhdWx0VGFnIiwiZGVmYXVsdFR5cG8iLCJ2YWxpZFR5cG9zIiwicHJvcHMiLCJ0eXBlIiwiU3RyaW5nIiwidmFsaWRhdG9yIiwiaW5kZXhPZiIsInZhbHVlIiwibWRjVGV4dFNlY3Rpb24iLCJtZGNUZXh0IiwibWl4aW5zIiwibWRjRGlzcGxheSIsIm1kY0hlYWRsaW5lIiwibWRjVGl0bGUiLCJtZGNTdWJIZWFkaW5nIiwibWRjQm9keSIsIm1kY0NhcHRpb24iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7Ozs7SUFBTyxTQUFTQSxRQUFULENBQWtCQyxNQUFsQixFQUEwQjtJQUMvQjtJQUNBLE1BQUlDLE9BQU8sSUFBWDtJQUNBLE1BQUksT0FBT0MsTUFB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
