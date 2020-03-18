import { REHYDRATE } from 'redux-persist/lib/constants';
import constants from '../constants/constants';

const initialState = [];

const portfolioUmumReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.PORTFOLIO_UMUM:
      return (state.portfolioUmum = action.data);
    default:
      return state;
  }
};

export default portfolioUmumReducer;
