import React, {Component} from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  TouchableHighlight,
  Dimensions,
  ToastAndroid,
} from 'react-native';
import {connect} from 'react-redux';
import {NavigationActions} from 'react-navigation';
import {color} from '../../../../styles/color';
import UUIDGenerator from 'react-native-uuid-generator';
import Icon from 'react-native-vector-icons/AntDesign';
import actions from '../../../../actions/';
import Header from '../../../../components/header/header';
import globalStyle from '../../../../styles/index';
import AssesseeItemRow from '../../../../components/assessiItemRow/assessiItemRow';
import LinearGradient from 'react-native-linear-gradient';
import FormInput from '../../../../components/formInput/formInput';
import Button from '../../../../components/button/button';
import Dialog from '../../../../components/dialog/dialog';
import constants from '../../../../constants/constants';
import Toast from 'react-native-easy-toast';

const {width, height} = Dimensions.get('window');

class ChooseAsesi extends Component {
  constructor(props) {
    super(props);
    this.state = {
      unixOrderId: '',
      unixId: '',
      namaLengkap: '',
      email: '',
      contact: '',
      institution: '',
      reRender: false,
    };
  }

  componentWillMount() {
    const {navigation} = this.props;
    const assign = navigation.getParam('assign');

    let existingId = this.props.asesi.listAsesi.map(i => i.id);
    let data = {
      id: assign.id,
      first_name: assign.namaLengkap,
      email: assign.email,
      contact: assign.contact,
      institution: assign.institution,
    };
    if (assign != null) {
      if (!existingId.includes(assign.id)) {
        this.props.addAsesi(data, response => this._callback(response));
      }
    } else {
      // ToastAndroid.show('tidak ada data', ToastAndroid.SHORT);
      this.refs.toast.show('Tidak ada data');
    }
  }

  componentDidMount() {
    UUIDGenerator.getRandomUUID(uuid => {
      this.setState({unixId: uuid});
    });
    UUIDGenerator.getRandomUUID(uuid => {
      this.setState({unixOrderId: uuid});
    });
  }

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);
  };

  _addToCart = () => {
    const title = this.props.navigation.getParam('title'); //title == nama_product
    const sub_schema_id = this.props.navigation.getParam('sub_schema_id'); //sub_schema_id == id product
    const addToCart = {
      orderId: this.state.unixOrderId,
      sub_schema_id: sub_schema_id,
      title: title,
      applicants: this.props.asesi.listAsesi,
      order_date: new Date(),
    };
    this.props.addCart(addToCart, response => response);
    this.props.gotoCart(true, response => response);
    this._finish();
  };

  _finish = () => {
    this.modals._closeDialog();
    this.props.navigation.reset(
      [
        NavigationActions.navigate({
          routeName: 'Menu',
        }),
      ],
      0,
    );
  };

  _addAsesi = () => {
    let {namaLengkap, email, contact, institution, unixId} = this.state;
    UUIDGenerator.getRandomUUID(uuid => {
      this.setState({unixId: uuid});
    });
    let data = {
      id: unixId,
      first_name: namaLengkap,
      email: email,
      contact: contact,
      institution: institution,
    };
    this.props.addAsesi(data, response => this._callback(response));
    this.modal._closeDialog();
  };

  _removeAsesi = unixId => {
    let data = {
      asesi: {
        id: unixId,
      },
    };
    this.props.removeAsesi(data, response => this._callback(response));
    setTimeout(() => {
      this.setState({reRender: !this.state.reRender});
    }, 10);
  };

  _callback(response) {
    console.log('==', response);
  }

  _openAddAsesiDialog() {
    this.setState({
      namaLengkap: '',
      email: '',
      contact: '',
      institution: '',
    });
    this.modal._openDialog();
  }

  render() {
    let {listAsesi} = this.props.asesi;
    return (
      <View style={globalStyle.containerBackground('center', 'white')}>
        <Header
          headerColor={color.green}
          onPressLeftIcon={() => this.props.navigation.goBack()}
          leftIconType="icon"
          leftIconColor="white"
          leftIconName="arrow-left"
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).choose_assessee
          }
          pageTitleColor="white"
        />
        <View style={{flex: 1, position: 'relative'}}>
          <ScrollView>
            {listAsesi.length > 0
              ? listAsesi.map((item, index) => {
                  console.log('asesi', item);
                  return (
                    <AssesseeItemRow
                      key={index}
                      name={item.first_name}
                      company={item.institution}
                      isLastIndex={false}>
                      <TouchableOpacity
                        onPress={() => this._removeAsesi(item.id)}>
                        <View
                          style={{
                            flex: 0.5,
                            justifyContent: 'center',
                            alignItems: 'flex-end',
                          }}>
                          <Icon name={'closecircle'} size={25} color={'red'} />
                        </View>
                      </TouchableOpacity>
                    </AssesseeItemRow>
                  );
                })
              : null}
            <TouchableOpacity onPress={() => this._openAddAsesiDialog()}>
              <AssesseeItemRow isLastIndex={true} />
            </TouchableOpacity>
          </ScrollView>
        </View>
        <Dialog
          title={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .add_other_assessee
          }
          ref={action => (this.modal = action)}>
          <View>
            <FormInput
              placeholder={
                constants.MULTILANGUAGE(this.props.settings.bahasa).first_name
              }
              keyboardType={'default'}
              value={this.state.namaLengkap}
              onChangeText={(type, value) =>
                this.onChangeText('namaLengkap', value)
              }
            />
            <View style={{height: 20}} />
            <FormInput
              placeholder={'EMAIL'}
              keyboardType={'email-address'}
              value={this.state.email}
              onChangeText={(type, value) => this.onChangeText('email', value)}
            />
            <View style={{height: 20}} />
            <FormInput
              placeholder={
                constants.MULTILANGUAGE(this.props.settings.bahasa).contact
              }
              keyboardType={'phone-pad'}
              value={this.state.contact}
              onChangeText={(type, value) =>
                this.onChangeText('contact', value)
              }
            />
            <View style={{height: 20}} />
            <FormInput
              placeholder={
                constants.MULTILANGUAGE(this.props.settings.bahasa).institution
              }
              keyboardType={'default'}
              value={this.state.institution}
              onChangeText={(type, value) =>
                this.onChangeText('institution', value)
              }
            />
            <View style={{height: 30}} />
            <Button
              title={
                constants.MULTILANGUAGE(this.props.settings.bahasa).add_assessee
              }
              titleColor={'white'}
              titleSize={16}
              onPressed={() => this._addAsesi()}
            />
          </View>
        </Dialog>
        <Dialog
          ref={action => (this.modals = action)}
          title={
            constants.MULTILANGUAGE(this.props.settings.bahasa).add_to_cart
          }
          description={
            constants.MULTILANGUAGE(this.props.settings.bahasa).add_to_cart_desc
          }>
          <View
            style={{
              flexDirection: 'row',
              justifyContent: 'flex-end',
            }}>
            <View style={{width: 90}}>
              <Button
                title={'Tidak'}
                titleColor={'white'}
                onPressed={() => this.modals._closeDialog()}
              />
            </View>
            <View style={{width: 10}} />
            <View style={{width: 90}}>
              <Button
                onPressed={() => this._addToCart()}
                title={'Ya'}
                titleColor={'white'}
              />
            </View>
          </View>
        </Dialog>
        <View style={{position: 'absolute', bottom: 0}}>
          <TouchableHighlight onPress={() => this.modals._openDialog()}>
            <LinearGradient
              end={{x: 1, y: 0}}
              colors={[color.green, color.lightGreen]}
              style={{
                width: width,
                height: 50,
                backgroundColor: color.green,
                justifyContent: 'center',
                alignItems: 'center',
              }}>
              <Text style={{color: color.white, fontSize: 15}}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .add_to_cart
                }
              </Text>
            </LinearGradient>
          </TouchableHighlight>
        </View>
        <Toast ref="toast" />
      </View>
    );
  }
}

const mapStateToProps = (state, ownProps) => {
  return {
    settings: state.settings,
    asesi: state.asesi,
    user: state.user,
  };
};

const mapDispatchToProps = dispatch => ({
  gotoCart: (data, callback) =>
    dispatch(actions.actionsAPI.cart.gotoCart(data, callback)),
  addCart: (data, callback) =>
    dispatch(actions.actionsAPI.cart.addCart(data, callback)),
  getAsesi: (data, callback) =>
    dispatch(actions.actionsAPI.asesi.getAsesi(data, callback)),
  addAsesi: (data, callback) =>
    dispatch(actions.actionsAPI.asesi.addAsesi(data, callback)),
  updateAsesi: (data, callback) =>
    dispatch(actions.actionsAPI.asesi.updateAsesi(data, callback)),
  removeAsesi: (data, callback) =>
    dispatch(actions.actionsAPI.asesi.removeAsesi(data, callback)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ChooseAsesi);
