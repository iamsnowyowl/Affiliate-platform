import React, { Component } from 'react';
import {
  View,
  Text,
  ScrollView,
  ImageBackground,
  TouchableOpacity,
  Dimensions,
  TouchableWithoutFeedback
} from 'react-native';
import { connect } from 'react-redux';
import { color } from '../../../../styles/color';
import { imgURL } from '../../../../assets/image/source';
import Header from '../../../../components/header/header';
import FormInput from '../../../../components/formInput/formInput';
import Button from '../../../../components/button/button';
import Icon from 'react-native-vector-icons/FontAwesome';
import LinearGradient from 'react-native-linear-gradient';
import Moment from 'moment';
import actions from '../../../../actions';
import constants from '../../../../constants/constants';

const { width, height } = Dimensions.get('window');

class OrderReview extends Component {
  constructor(props) {
    super(props);
    this.state = { code: '' };
  }

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);
  };

  render() {
    let { orders } = this.props.cart;
    return (
      <View style={{ flex: 1, backgroundColor: 'white' }}>
        <Header
          leftIconType="icon"
          headerColor={color.green}
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).order_review
          }
          pageTitleColor="white"
          leftIconColor="white"
          leftIconName="arrow-left"
          onPressLeftIcon={() => this.props.navigation.goBack()}
        />
        <ScrollView style={{ marginBottom: 50 }}>
          <ScrollView
            style={{
              paddingTop: 10,
              paddingHorizontal: 20,
              height: 200
            }}
            horizontal={true}
            alwaysBounceHorizontal={true}
            directionalLockEnabled={true}
            snapToInterval={305}
            snapToAlignment={'start'}
            showsHorizontalScrollIndicator={false}
            pagingEnabled={true}
            scrollEventThrottle={8}
          >
            {orders.map((item, index) => {
              return (
                <View
                  key={index}
                  style={{
                    position: 'relative',
                    marginTop: 10,
                    marginRight: 15,
                    elevation: 5
                  }}
                >
                  <ImageBackground
                    source={{ uri: imgURL.imageSource }}
                    style={{
                      width: 290,
                      height: 150,
                      overflow: 'hidden',
                      borderRadius: 5,
                      backgroundColor: color.greyPlaceholder
                    }}
                  >
                    <View
                      style={{
                        height: 150,
                        backgroundColor: color.transparent
                      }}
                    >
                      <View
                        style={{ position: 'absolute', bottom: 20, left: 20 }}
                      >
                        <Text
                          style={{
                            fontWeight: '200',
                            color: 'white',
                            fontSize: 12
                          }}
                        >
                          {Moment(item.order_date).format('DD MMMM YYYY')}
                        </Text>
                        <Text style={{ fontWeight: '500', color: 'white' }}>
                          {item.title}
                        </Text>
                        <View style={{ height: 5 }} />
                        <Text
                          style={{
                            fontWeight: '400',
                            color: 'white'
                          }}
                        >
                          Rp. 6000.000
                        </Text>
                      </View>
                    </View>
                  </ImageBackground>
                </View>
              );
            })}
          </ScrollView>
          <View>
            <View
              style={{
                paddingRight: 110,
                paddingLeft: 20,
                paddingBottom: 20,
                borderBottomWidth: 1,
                borderBottomColor: color.darkGrey
              }}
            >
              <FormInput
                keyboardType="default"
                placeholder={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .discount_code
                }
                ref="code"
                type="code"
                value={this.state.code}
                onChangeText={(type, value) => this.onChangeText('code', value)}
              />
              <TouchableOpacity
                style={{ position: 'absolute', right: 20, bottom: 20 }}
              >
                <LinearGradient
                  start={{ x: 0, y: 0 }}
                  end={{ x: 1, y: 0 }}
                  colors={[color.green, color.lightGreen]}
                  style={{
                    borderRadius: 5,
                    backgroundColor: color.green,
                    height: 42,
                    width: 80,
                    justifyContent: 'center',
                    alignItems: 'center'
                  }}
                >
                  <Text
                    style={{
                      color: 'white'
                    }}
                  >
                    {constants.MULTILANGUAGE(this.props.settings.bahasa).redeem}
                  </Text>
                </LinearGradient>
              </TouchableOpacity>
            </View>
            <View
              style={{
                flex: 1,
                flexDirection: 'row',
                paddingHorizontal: 20,
                marginTop: 10
              }}
            >
              <Text style={{ color: 'black', fontWeight: '600' }}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .payment_method
                }
              </Text>
              <TouchableOpacity
                style={{
                  position: 'absolute',
                  right: 20
                }}
              >
                <Text
                  style={{
                    color: color.green,
                    fontWeight: '600'
                  }}
                >
                  {
                    constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .bank_transfer
                  }
                </Text>
              </TouchableOpacity>
            </View>
            <View
              style={{
                flexDirection: 'row',
                height: 50,
                paddingHorizontal: 20,
                alignItems: 'center',
                borderBottomWidth: 1,
                borderBottomColor: color.darkGrey
              }}
            >
              <Text style={{ color: color.darkGrey, fontWeight: '500' }}>
                SUBTOTAL
              </Text>
              <Text
                style={{
                  position: 'absolute',
                  right: 20,
                  color: 'black',
                  fontWeight: '500'
                }}
              >
                Rp. 60.000.000
              </Text>
            </View>
            <View
              style={{
                flexDirection: 'row',
                height: 50,
                paddingHorizontal: 20,
                alignItems: 'center',
                borderBottomWidth: 1,
                borderBottomColor: color.darkGrey
              }}
            >
              <Text style={{ color: color.darkGrey, fontWeight: '500' }}>
                {constants.MULTILANGUAGE(this.props.settings.bahasa).discount}
              </Text>
              <Text
                style={{
                  position: 'absolute',
                  right: 20,
                  color: 'black',
                  fontWeight: '500'
                }}
              >
                -Rp. 500.000
              </Text>
            </View>
            <View
              style={{
                flexDirection: 'row',
                height: 50,
                paddingHorizontal: 20,
                alignItems: 'center',
                borderBottomWidth: 1,
                borderBottomColor: color.darkGrey
              }}
            >
              <Text style={{ color: color.darkGrey, fontWeight: '500' }}>
                TOTAL
              </Text>
              <Text
                style={{
                  position: 'absolute',
                  right: 20,
                  color: 'black',
                  fontWeight: '500'
                }}
              >
                Rp. 59.500.000
              </Text>
            </View>
          </View>
        </ScrollView>
        <TouchableWithoutFeedback>
          <LinearGradient
            start={{ x: 0, y: 0 }}
            end={{ x: 1, y: 0 }}
            colors={[color.green, color.lightGreen]}
            style={{
              width: width,
              position: 'absolute',
              bottom: 0,
              height: 50,
              backgroundColor: color.green,
              justifyContent: 'center',
              alignItems: 'center'
            }}
          >
            <Text style={{ fontWeight: '600', color: 'white' }}>
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .proceed_payment
              }
            </Text>
          </LinearGradient>
        </TouchableWithoutFeedback>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  cart: state.cart,
  settings: state.settings,
  orders: state.orders
});

const mapDispatchToProps = dispatch => ({});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(OrderReview);
