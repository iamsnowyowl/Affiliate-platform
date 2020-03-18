import constants from '../../constants/constants';

const actionsReducer = {};

actionsReducer.get = data => ({
  type: constants.GET_ASESI,
  data: data
});

actionsReducer.add = data => ({
  type: constants.ADD_ASESI,
  data: data
});

actionsReducer.update = data => ({
  type: constants.UPDATE_ASESI,
  data: data
});

actionsReducer.remove = data => ({
  type: constants.REMOVE_ASESI,
  data: data
});

export default actionsReducer;
