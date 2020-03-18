import constants from '../constants/constants';
import { REHYDRATE } from 'redux-persist/lib/constants';

const initialState = [];

const articlesReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.ARTICLES:
      return (state = action.data);
    default:
      return state;
  }
};

export default articlesReducer;
