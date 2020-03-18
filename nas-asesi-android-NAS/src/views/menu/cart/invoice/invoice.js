import React, { Component } from 'react';
import {
  View,
  Text,
  ScrollView,
  ImageBackground,
  TouchableOpacity
} from 'react-native';
import { connect } from 'react-redux';
import { imgURL } from '../../../../assets/image/source';
import { color } from '../../../../styles/color';
import Moment from 'moment';
import LinearGradient from 'react-native-linear-gradient';
import Button from '../../../../components/button/button';
import constants from '../../../../constants/constants';
import EmptyContainer from '../../../../components/emptyContainer/emptyContainer';

class TabInvoice extends Component {
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
                    this.props.screenProps.navigate('InvoiceDetail')
                  }
                >
                  <View
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
                    <LinearGradient
                      end={{ x: 1, y: 0 }}
                      colors={[color.green, color.lightGreen]}
                      style={{
                        position: 'absolute',
                        right: 25,
                        top: 15,
                        borderRadius: 15,
                        justifyContent: 'center',
                        height: 25,
                        paddingHorizontal: 10
                      }}
                    >
                      <Text style={{ textAlign: 'center', color: 'white' }}>
                        ON PROCESS
                      </Text>
                    </LinearGradient>
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
)(TabInvoice);
