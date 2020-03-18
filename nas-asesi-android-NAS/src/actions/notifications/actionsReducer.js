import constants from '../../constants/constants';

const actionsReducer = {};

actionsReducer.notification = data => ({
  type: constants.NOTIFICATION,
  data: data
});

export default actionsReducer;
