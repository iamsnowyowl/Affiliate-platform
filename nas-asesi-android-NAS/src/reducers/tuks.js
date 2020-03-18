import { REHYDRATE } from 'redux-persist/lib/constants';
import constants from '../constants/constants';

const initialState = { tuks: [] };

const tukReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.TUK_LIST:
      return (state.tuks = action.data);
    default:
      return state;
  }
};

export default tukReducer;
