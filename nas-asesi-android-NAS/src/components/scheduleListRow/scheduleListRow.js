import React, { Component } from 'react';
import {
  View,
  Text,
  StyleSheet,
  Dimensions,
  Image,
  TouchableWithoutFeedback
} from 'react-native';
import { color } from '../../styles/color';
const { width, height } = Dimensions.get('window');

export default class ScheduleListRow extends Component {
  constructor(props) {
    super(props);
    this.state = { selected: false };
  }
  render() {
    return (
      <TouchableWithoutFeedback
        onPress={() => this.setState({ selected: !this.state.selected })}
      >
        <View style={styles.cardContainer(this.state.selected)}>
          <View>
            <View
              style={{
                flex: 1,
                flexDirection: 'row',
                justifyContent: 'space-between'
              }}
            >
              <View style={{ flex: 1 }}>
                <Text style={styles.text(this.state.selected, 'normal', 14)}>
                  Assessment Date
                </Text>
                <Text
                  numberOfLines={2}
                  style={styles.text(this.state.selected, '600', 16)}
                >
                  01 - 04 January 2018
                </Text>
              </View>
              <View style={{ flex: 1 }}>
                <Text style={styles.text(this.state.selected, 'normal', 14)}>
                  Time
                </Text>
                <Text
                  numberOfLines={2}
                  style={styles.text(this.state.selected, '600', 16)}
                >
                  08.00 AM - 16.00 PM
                </Text>
              </View>
            </View>
            <View
              style={{
                marginVertical: 20,
                height: 1,
                flex: 1,
                backgroundColor: color.darkGrey
              }}
            />
            <View
              style={{ flex: 1, flexDirection: 'row', alignItems: 'center' }}
            >
              <View style={styles.roundImage}>
                <Image
                  style={{ width: 50, height: 50 }}
                  source={{
                    uri:
                      'https://s3-prod.crainsdetroit.com/s3fs-public/NEWS_170429967_AR_0_UIWDULVHSJAF.jpg'
                  }}
                />
              </View>
              <View>
                <Text style={styles.text(this.state.selected, 'normal', 14)}>
                  Assessor
                </Text>
                <Text style={styles.text(this.state.selected, '600', 16)}>
                  Dr. Eng. Ian To
                </Text>
              </View>
            </View>
          </View>
        </View>
      </TouchableWithoutFeedback>
    );
  }
}

const styles = StyleSheet.create({
  cardContainer: selected => ({
    borderRadius: 10,
    backgroundColor: selected ? color.green : color.greyPlaceholder,
    width: width - 40,
    padding: 20,
    marginBottom: 10
  }),
  text: (fontColor, fontWeight, fontSize) => ({
    color: fontColor ? color.white : color.black,
    fontSize: fontSize,
    marginBottom: 5,
    fontWeight: fontWeight
  }),
  roundImage: {
    width: 50,
    height: 50,
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 50,
    overflow: 'hidden',
    marginRight: 15,
    alignSelf: 'center',
    backgroundColor: color.white
  }
});
