import constants from '../../constants/constants';

const actionsReducer = {};

actionsReducer.articles = data => ({
  type: constants.ARTICLES,
  data: data
});

export default actionsReducer;
