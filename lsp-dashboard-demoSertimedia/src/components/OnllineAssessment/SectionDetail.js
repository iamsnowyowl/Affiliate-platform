import React from 'react';
import { Checkbox } from 'antd';

import 'antd/dist/antd.css';
import '../../css/FormOnline.css';
import Axios from 'axios';
import {
  getData,
  path_kukSection,
  path_kukSection_detail
} from '../config/config';

type Props = {
  kuk_section_id: '',
  checkAll: any
};

class SectionDetail extends React.Component<Props> {
  state = {
    checkedList: '',
    // indeterminate: false,
    // checkAll: false,
    payloadSectionDetail: []
  };

  componentDidMount() {
    Axios(
      getData(
        path_kukSection +
          '/' +
          this.props.kuk_section_id +
          path_kukSection_detail,
        'GET'
      )
    ).then(response => {
      this.setState({
        payloadSectionDetail: response.data.data
      });
    });
  }

  onChange = kuk_section_detail_id => {
    console.log('on Change detail');
    // this.setState({
    //   checkedList,
    //   indeterminate:
    //     !!checkedList.length && checkedList.length < plainOptions.length,
    //   checkAll: checkedList.length === plainOptions.length
    // });
  };

  // onCheckAllChange = e => {
  //   this.setState({
  //     checkedList: e.target.checked ? plainOptions : [],
  //     indeterminate: false,
  //     checkAll: e.target.checked
  //   });
  // };

  render() {
    const { payloadSectionDetail } = this.state;
    return (
      <div>
        {payloadSectionDetail.map(
          ({ kuk_section_detail_id, question }, key) => {
            return (
              <div style={{ marginLeft: '25px' }}>
                <Checkbox
                  onChange={this.onChange.bind(this, kuk_section_detail_id)}
                >
                  {question}
                </Checkbox>
              </div>
            );
          }
        )}
        {/* <Checkbox
            indeterminate={this.state.indeterminate}
            onChange={this.onCheckAllChange}
            checked={this.state.checkAll}
          >
            Check All
          </Checkbox> */}
        {/* <br />
        <CheckboxGroup
          options={plainOptions}
          value={this.state.checkedList}
          onChange={this.onChange}
        /> */}
      </div>
    );
  }
}

export default SectionDetail;
