import constants from '../../constants/constants';

const actionsReducer = {};

actionsReducer.bahasa = data => ({
  type: constants.MULTI_LANGUANGE,
  data: { bahasa: data }
});

actionsReducer.firstTime = data => ({
  type: constants.FIRST_TIME,
  data: { first_time: data }
});

actionsReducer.fcmToken = data => ({
  type: constants.FCM_TOKEN,
  data: { fcm_token: data }
});

export default actionsReducer;
