import language from './configLanguge';

export const _getBahasa = () => {
  // console.log('get bahasa', localStorage.getItem('bahasa'));
  if (localStorage.getItem('bahasa') === null) {
    return 'id';
  } else {
    return localStorage.getItem('bahasa');
  }
};

export const multiLanguage = language.MultiLanguageTitle(_getBahasa());
