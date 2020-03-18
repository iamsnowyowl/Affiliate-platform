import constants from '../../constants/constants';

const actionsReducer = {};

actionsReducer.me = data => ({
  type: constants.ME,
  data: data
});

export default actionsReducer;
