import React, { Component } from 'react';
import {
  View,
  Text,
  ScrollView,
  Image,
  Dimensions,
  TouchableOpacity
} from 'react-native';
import { connect } from 'react-redux';
import { imgURL } from '../../../../assets/image/source';
import { color } from '../../../../styles/color';
import Moment from 'moment';
import constants from '../../../../constants/constants';
import Button from '../../../../components/button/button';
import LinearGradient from 'react-native-linear-gradient';
import EmptyContainer from '../../../../components/emptyContainer/emptyContainer';

const { height, width } = Dimensions.get('window');

class TabHistory extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    let { orders } = this.props.cart;
    return (
      <View style={{ flex: 1, justifyContent: 'center' }}>
        {orders.length > 0 ? (
          <ScrollView>
            {orders.map((item, index) => {
              return (
                <TouchableOpacity
                  key={index}
                  onPress={() =>
                    this.props.screenProps.navigate('TransactionDetail')
                  }
                >
                  <View
                    style={{
                      borderWidth: 1,
                      borderColor: color.greyPlaceholder,
                      marginTop: 10,
                      marginHorizontal: 20
                    }}
                  >
                    <LinearGradient
                      end={{ x: 1, y: 0 }}
                      colors={[color.green, color.lightGreen]}
                      style={{ height: 25, justifyContent: 'center' }}
                    >
                      <Text style={{ color: 'white', textAlign: 'center' }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .successful_transaction
                        }
                      </Text>
                    </LinearGradient>
                    <View
                      style={{
                        padding: 10,
                        borderBottomColor: color.greyPlaceholder,
                        borderBottomWidth: 1
                      }}
                    >
                      <Text style={{ color: color.darkGrey }}>
                        {Moment(item.order_date).format('DD MMMM YYYY')}
                      </Text>
                      <View style={{ height: 5 }} />
                      <Text style={{ color: 'black' }}>
                        INV/20190428/UV/9764868
                      </Text>
                    </View>
                    <View
                      style={{
                        padding: 10,
                        flexDirection: 'row'
                      }}
                    >
                      <Image
                        source={{ uri: imgURL.imageSource }}
                        style={{ width: 100 }}
                      />
                      <View
                        style={{
                          marginLeft: 10,
                          width: width - 170
                        }}
                      >
                        <Text style={{ color: 'black', fontWeight: 'bold' }}>
                          {
                            constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .certificate
                          }{' '}
                          :{' '}
                          <Text style={{ fontWeight: 'normal' }}>
                            {item.title}
                          </Text>
                        </Text>
                        <View style={{ height: 5 }} />
                        <Text style={{ color: 'black', fontWeight: 'bold' }}>
                          {
                            constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .assessment_date
                          }{' '}
                          <Text style={{ fontWeight: 'normal' }}>
                            {Moment(item.order_date).format('DD MMMM YYYY')}
                          </Text>
                        </Text>
                        <View style={{ height: 5 }} />
                        <Text style={{ color: 'black', fontWeight: 'bold' }}>
                          {
                            constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .organizer
                          }{' '}
                          :{' '}
                          <Text style={{ fontWeight: 'normal' }}>
                            LSP Energi
                          </Text>
                        </Text>
                      </View>
                    </View>
                    <View style={{ paddingHorizontal: 10, paddingBottom: 15 }}>
                      <Text style={{ color: 'black' }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .total_cost
                        }
                      </Text>
                      <View style={{ height: 5 }} />
                      <Text style={{ color: color.green }}>Rp. 6.000.000</Text>
                    </View>
                  </View>
                </TouchableOpacity>
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
      </View>
    );
  }
}

const mapStateToProps = state => ({
  cart: state.cart,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(TabHistory);
