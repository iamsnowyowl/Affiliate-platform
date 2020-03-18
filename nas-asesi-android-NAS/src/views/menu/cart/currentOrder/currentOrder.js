import React, { Component } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  Dimensions,
  ImageBackground
} from 'react-native';
import { color } from '../../../../styles/color';
import { connect } from 'react-redux';
import { imgURL } from '../../../../assets/image/source';
import Moment from 'moment';
import Icon from 'react-native-vector-icons/FontAwesome';
import actions from '../../../../actions';
import EmptyContainer from '../../../../components/emptyContainer/emptyContainer';
import Button from '../../../../components/button/button';
import constants from '../../../../constants/constants';

const { width, height } = Dimensions.get('window');

class TabCurrentOrder extends Component {
  constructor(props) {
    super(props);
    this.state = {
      reRender: false
    };
  }

  _removeCart = orderId => {
    let data = {
      orders: {
        orderId: orderId
      }
    };
    this.props.removeCart(data, response => response);
    setTimeout(() => {
      this.setState({ reRender: !this.state.reRender });
    }, 10);
  };

  render() {
    let { orders } = this.props.cart;
    let total = orders.reduce((totalorder, order) => totalorder + 60000, 0); // counting total value from array
    return (
      <View style={{ flex: 1, justifyContent: 'center' }}>
        {orders.length > 0 ? (
          <ScrollView>
            {orders.map((item, index) => {
              return (
                <View
                  key={index}
                  style={{
                    position: 'relative',
                    paddingHorizontal: 20,
                    paddingTop: 10,
                    paddingBottom: 10
                  }}
                >
                  <ImageBackground
                    source={{ uri: imgURL.imageSource }}
                    style={{
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
                            fontWeight: 'normal',
                            color: 'white',
                            fontSize: 12
                          }}
                        >
                          {Moment(item.order_date).format('DD MMMM YYYY')}
                        </Text>
                        <Text style={{ fontWeight: 'bold', color: 'white' }}>
                          {item.title}
                        </Text>
                        <View style={{ height: 5 }} />
                        <Text
                          style={{
                            fontWeight: 'normal',
                            color: 'white'
                          }}
                        >
                          Rp. 6000.000
                        </Text>
                      </View>
                    </View>
                  </ImageBackground>
                  <TouchableOpacity
                    onPress={() => this._removeCart(item.orderId)}
                    style={{
                      position: 'absolute',
                      right: 12,
                      top: 0,
                      padding: 5
                    }}
                  >
                    <Icon name="times-circle" color={'red'} size={25} />
                  </TouchableOpacity>
                </View>
              );
            })}
          </ScrollView>
        ) : (
          <EmptyContainer
            emptyText={
              constants.MULTILANGUAGE(this.props.settings.bahasa).empty_cart
            }
          >
            <Button
              onPressed={() => this.props.screenProps.navigate('Discover')}
              title={
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .discover_certifications
              }
              titleSize={13}
              titleColor={'white'}
            />
          </EmptyContainer>
        )}
        {orders.length > 0 ? (
          <View
            style={{
              position: 'absolute',
              width: width,
              paddingHorizontal: 20,
              bottom: 20
            }}
          >
            <Button
              onPressed={() => this.props.screenProps.navigate('OrderReview')}
              title={'Check Out'}
              titleSize={13}
              titleColor={'white'}
            />
          </View>
        ) : null}
      </View>
    );
  }
}

const mapStateToProps = state => ({
  cart: state.cart,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  removeCart: (data, callback) =>
    dispatch(actions.actionsAPI.cart.removeCart(data, callback))
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(TabCurrentOrder);
