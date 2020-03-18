import constants from '../../constants/constants';

const actionsReducer = {};

actionsReducer.portfolioDasar = data => ({
  type: constants.PORTFOLIO_DASAR,
  data: data
});

actionsReducer.portfolioUmum = data => ({
  type: constants.PORTFOLIO_UMUM,
  data: data
});

export default actionsReducer;
