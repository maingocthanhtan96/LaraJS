import { scrollTo } from '@/utils/scroll-to';

export default {
  methods: {
    scrollToError(valid, errors) {
      if (!valid) {
        if (document.getElementsByName(Object.keys(errors)[0]).length) {
          scrollTo(
            document
              .getElementsByName(Object.keys(errors)[0])[0]
              .getBoundingClientRect().top -
              document.body.getBoundingClientRect().top -
              130,
            800
          );
        }
        return true;
      }
      return false;
    },
  },
};
