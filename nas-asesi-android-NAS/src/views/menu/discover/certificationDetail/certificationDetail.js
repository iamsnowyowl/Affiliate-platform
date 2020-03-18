import React, { Component } from 'react';
import {
  View,
  Text,
  ImageBackground,
  Dimensions,
  Image,
  TouchableHighlight,
  ScrollView
} from 'react-native';
import { imgURL } from '../../../../assets/image/source';
import { color } from '../../../../styles/color';
import { connect } from 'react-redux';
import LinearGradient from 'react-native-linear-gradient';
import Header from '../../../../components/header/header';
import Icon from 'react-native-vector-icons/FontAwesome';
import constants from '../../../../constants/constants';

const { width, height } = Dimensions.get('window');

class CertificationDetail extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    const { navigation } = this.props;
    const sub_schema_id = navigation.getParam(
      'sub_schema_id',
      'Id Not Available'
    );
    const title = navigation.getParam('title', 'Title Not Available');
    const price = navigation.getParam('price', 'Price Not Available');
    return (
      <View style={{ flex: 1 }}>
        <Header
          onPressLeftIcon={() => this.props.navigation.goBack()}
          leftIconType="icon"
          leftIconColor="white"
          leftIconName={'arrow-left'}
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .certification_detail
          }
          pageTitleColor="white"
          headerColor={color.green}
        />
        <ImageBackground
          source={{ uri: imgURL.imageSource }}
          style={{ width: width, height: 250 }}
        >
          <View
            style={{
              position: 'absolute',
              bottom: 20,
              left: 20,
              width: width - 40
            }}
          >
            <View>
              <Text
                style={{ fontSize: 20, color: 'white', fontWeight: 'bold' }}
              >
                {title}
              </Text>
              <Text
                style={{ fontSize: 20, color: 'white', fontWeight: 'normal' }}
              >
                {price}
              </Text>
            </View>
          </View>
        </ImageBackground>
        <ScrollView style={{ zIndex: 0 }}>
          <View style={styles.container}>
            <View style={styles.centerContainer}>
              <View style={{ flexDirection: 'row' }}>
                <View style={styles.roundIcon}>
                  <Icon name="tag" size={18} color="white" />
                </View>
                <View>
                  <Text style={{ color: 'black', fontWeight: 'normal' }}>
                    {constants.MULTILANGUAGE(this.props.settings.bahasa).fee}
                  </Text>
                  <Text style={{ color: 'black', fontWeight: 'bold' }}>
                    {price}
                  </Text>
                </View>
              </View>
              <View style={{ flexDirection: 'row' }}>
                <View style={styles.roundIcon}>
                  <Icon name="list-alt" size={18} color="white" />
                </View>
                <View>
                  <Text style={{ color: 'black', fontWeight: 'normal' }}>
                    {
                      constants.MULTILANGUAGE(this.props.settings.bahasa)
                        .validity_period
                    }
                  </Text>
                  <Text style={{ color: 'black', fontWeight: 'bold' }}>
                    1 Year
                  </Text>
                </View>
              </View>
            </View>
            <View>
              <Text
                style={{
                  color: color.black,
                  fontSize: 17,
                  marginBottom: 20,
                  fontWeight: 'bold'
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).overview}
              </Text>
              <Text style={{ color: color.black, marginBottom: 15 }}>
                Lorem Ipsum is simply dummy text of the printing and typesetting
                industry. Lorem Ipsum has been the industry's standard dummy
                text ever since the 1500s, when an unknown printer took a galley
                of type and scrambled it to make a type specimen book. It has
                survived not only five centuries, but also the leap into
                electronic typesetting, remaining essentially unchanged. It was
                popularised in the 1960s with the release of Letraset sheets
                containing Lorem Ipsum passages, and more recently with desktop
                publishing software like Aldus PageMaker including versions of
                Lorem Ipsum.
              </Text>
              <Text
                style={{
                  color: color.black,
                  fontSize: 17,
                  marginBottom: 20,
                  fontWeight: 'bold'
                }}
              >
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .lsp_organizer
                }
              </Text>
              <View
                style={{
                  flexDirection: 'row',
                  paddingBottom: 50,
                  alignItems: 'center'
                }}
              >
                <View style={styles.roundImage}>
                  <Image
                    style={{ width: 50, height: 50 }}
                    source={{
                      uri:
                        'https://bnsp.go.id/images/YLeRszbDCFJP9Wga1X5fqOk8Mm2hHvGT.jpg'
                    }}
                  />
                </View>
                <Text
                  style={{ fontWeight: 'bold', fontSize: 20, color: 'black' }}
                >
                  BNSP - Jakarta Selatan
                </Text>
              </View>
            </View>
          </View>
        </ScrollView>
        <View style={{ position: 'absolute', bottom: 0 }}>
          <TouchableHighlight
            onPress={() =>
              this.props.navigation.navigate('ScheduleList', {
                sub_schema_id: sub_schema_id,
                title: title
              })
            }
          >
            <LinearGradient
              start={{ x: 0, y: 0 }}
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
                    .join_certificate
                }
              </Text>
            </LinearGradient>
          </TouchableHighlight>
        </View>
      </View>
    );
  }
}

const styles = {
  container: {
    padding: 20
  },
  roundIcon: {
    width: 30,
    height: 30,
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 50,
    overflow: 'hidden',
    marginRight: 15,
    alignSelf: 'center',
    backgroundColor: color.green
  },
  roundImage: {
    width: 50,
    height: 50,
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 50,
    overflow: 'hidden',
    marginRight: 15,
    alignSelf: 'center',
    backgroundColor: 'white'
  },
  centerContainer: {
    justifyContent: 'space-between',
    flexDirection: 'row',
    paddingVertical: 10,
    paddingHorizontal: 15,
    marginBottom: 15,
    borderRadius: 10,
    borderColor: color.greyPlaceholder,
    borderWidth: 2
  }
};

const mapStateToProps = state => ({
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(CertificationDetail);
