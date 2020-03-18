import React, { Component } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableHighlight,
  Dimensions
} from 'react-native';
import { connect } from 'react-redux';
import { color } from '../../../../styles/color';
import constants from '../../../../constants/constants';
import globalStyle from '../../../../styles/index';
import Header from '../../../../components/header/header';
import LinearGradient from 'react-native-linear-gradient';
import ScheduleListRow from '../../../../components/scheduleListRow/scheduleListRow';
const { width, height } = Dimensions.get('window');

class ScheduleList extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  _gotoNextPage() {
    const { navigation } = this.props;
    const sub_schema_id = navigation.getParam(
      'sub_schema_id',
      'Id Not Available'
    );
    const title = navigation.getParam('title', 'Title Not Available');

    const data = {
      id: this.props.user.user_id,
      namaLengkap: this.props.user.first_name + ' ' + this.props.user.last_name,
      email: this.props.user.email,
      contact: this.props.user.contact,
      institution: this.props.user.institution
    };
    this.props.navigation.navigate('ChooseAsesi', {
      assign: data,
      sub_schema_id: sub_schema_id,
      title: title
    });
  }

  render() {
    return (
      <View style={globalStyle.containerBackground('center', 'white')}>
        <Header
          headerColor={color.green}
          onPressLeftIcon={() => this.props.navigation.goBack()}
          leftIconType="icon"
          leftIconColor="white"
          leftIconName="arrow-left"
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).schedule_list
          }
          pageTitleColor="white"
        />
        <View style={{ flex: 1, position: 'relative' }}>
          <ScrollView>
            <View style={{ paddingTop: 20 }}>
              <ScheduleListRow />
            </View>
          </ScrollView>
        </View>
        <View style={{ position: 'absolute', bottom: 0 }}>
          <TouchableHighlight onPress={() => this._gotoNextPage()}>
            <LinearGradient
              end={{ x: 1, y: 0 }}
              colors={[color.green, color.lightGreen]}
              style={{
                width: width,
                height: 50,
                backgroundColor: color.green,
                justifyContent: 'center',
                alignItems: 'center'
              }}
            >
              <Text style={{ color: color.white, fontSize: 15 }}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .choose_schedule
                }
              </Text>
            </LinearGradient>
          </TouchableHighlight>
        </View>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  user: state.user,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  // getSchema: (data, callback) =>
  //   dispatch(actions.actionsAPI.discover.getSchema(data, callback))
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(ScheduleList);
