import { REHYDRATE } from 'redux-persist/lib/constants';
import constants from '../constants/constants';

const initialState = [];

const notificationReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.NOTIFICATION_CLUSTERS:
      return (state = action.data);
    default:
      return state;
  }
};

export default notificationReducer;
