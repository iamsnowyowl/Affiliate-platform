import React, { Component } from 'react';
import { View, Text, Image, Dimensions } from 'react-native';
import { color } from '../../styles/color';

const { width, height } = Dimensions.get('window');

type Props = {
  emptyText: any
};
export default class EmptyContainer extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    const { emptyText, children } = this.props;
    return (
      <View style={styles.container}>
        <Image
          style={styles.img}
          source={require('../../assets/image/empty.png')}
        />
        <View style={{ height: 15 }} />
        <Text style={styles.text}>{emptyText}</Text>
        <View style={{ height: 35 }} />
        <View style={{ width: width - 150 }}>{children}</View>
      </View>
    );
  }
}

const styles = {
  container: {
    width: width - 100,
    justifyContent: 'center',
    alignItems: 'center',
    alignSelf: 'center'
  },
  img: {
    resizeMode: 'stretch',
    width: width - 100,
    height: 100,
    justifyContent: 'center',
    alignItems: 'center'
  },
  text: {
    textAlign: 'center',
    fontWeight: 'normal',
    fontSize: 15,
    color: color.greyPlaceholder
  }
};
