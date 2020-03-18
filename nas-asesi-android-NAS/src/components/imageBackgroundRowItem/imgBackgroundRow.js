import React, { Component } from 'react';
import { View, Text, ImageBackground } from 'react-native';
import { color } from '../../styles/color';
import imgBackgroundStyle from '../imageBackgroundRowItem/style';
import globalStyle from '../../styles/index';

type Props = {
  imgSource: any,
  titleItemRow: any,
  descItemRow: any
};
export default class imageBackgroundRow extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    const { imgSource, titleItemRow, descItemRow } = this.props;
    return (
      <View style={imgBackgroundStyle.containerBody}>
        <ImageBackground
          source={{ uri: imgSource }}
          style={imgBackgroundStyle.containerImageBackground}
        >
          <View
            style={{
              position: 'relative',
              backgroundColor: color.transparent,
              flex: 1
            }}
          >
            <View
              style={{
                position: 'absolute',
                paddingHorizontal: 10,
                paddingBottom: 10,
                marginRight: 20,
                bottom: 0,
                left: 0,
                right: 0
              }}
            >
              <Text
                style={{
                  fontWeight: 'bold',
                  color: 'white'
                }}
              >
                {titleItemRow}
              </Text>
              <View style={{ height: 5 }} />
              <Text style={{ fontWeight: 'bold', color: 'white' }}>
                {descItemRow}
              </Text>
            </View>
          </View>
        </ImageBackground>
      </View>
    );
  }
}
