import React, { Component } from 'react';
import { View, Text, Dimensions, Platform } from 'react-native';
import { color } from '../../styles/color';
import Moment from 'moment';
import rowStyle from '../rowItem/style';
import Icon from 'react-native-vector-icons/FontAwesome';
import globalStyle from '../../styles/index';

const { width, height } = Dimensions.get('window');

type Props = {
  title: any,
  description: any,
  time: any,
  status: any
};
export default class RowItem extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }
  render() {
    const { title, description, time, status } = this.props;
    return (
      <View style={rowStyle.container}>
        <View style={{ width: width - 70 }}>
          <Text style={globalStyle.text(14, 5, '700', color.black)}>
            {title}
          </Text>
          <Text style={globalStyle.text(12, 0, 'normal', color.darkGrey)}>
            {Moment(time).format('DD MMMM YYYY')}
          </Text>
          <View style={{ height: 8 }} />
          <Text style={globalStyle.text(14, 0, 'normal', color.darkGrey)}>
            {description}
          </Text>
        </View>
        <View style={rowStyle.roundIcon}>
          <Text style={{ color: 'white', fontSize: 11, fontWeight: 'bold' }}>
            {status}
          </Text>
        </View>
      </View>
    );
  }
}
