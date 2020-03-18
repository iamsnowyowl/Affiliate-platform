import React from 'react';
import { Checkbox } from 'antd';

import 'antd/dist/antd.css';
import '../../css/FormOnline.css';
import Axios from 'axios';
import {
  getData,
  path_formOnline,
  path_kukSection,
  path_kukSection_detail
} from '../config/config';
import SectionDetail from './SectionDetail';

type Props = {
  kuk_id: ''
};

const CheckboxGroup = Checkbox.Group;

const plainOptions = [
  'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa',
  'Phasellus viverra nulla ut metus variuslaoreet. Quisque rutrum. Aenean im'
];

class Bagian1 extends React.Component<Props> {
  state = {
    checkedList: '',
    indeterminate: false,
    checkAll: false,
    payloadKukSection: [],
    payloadKukSectionDetail: []
  };

  componentDidMount() {
    Axios(
      getData(
        path_formOnline + '/' + this.props.kuk_id + path_kukSection,
        'GET'
      )
    ).then(response => {
      const data = response.data.data;
      this.setState({
        payloadKukSection: data
      });
    });
  }

  onChange = checkedList => {
    this.setState({
      checkedList,
      indeterminate:
        !!checkedList.length && checkedList.length < plainOptions.length,
      checkAll: checkedList.length === plainOptions.length
    });
  };

  onCheckAllChange = e => {
    this.setState({
      checkedList: e.target.checked ? plainOptions : [],
      indeterminate: false,
      checkAll: e.target.checked
    });
  };

  render() {
    const {
      payloadKukSection,
      payloadKukSectionDetail,
      checkAll,
      checkedList,
      indeterminate
    } = this.state;
    return (
      <div>
        {payloadKukSection.map(({ kuk_section_id, kuk_section_name }, key) => {
          return (
            <div
              style={{
                borderBottom: '1px solid #E9E9E9',
                marginBottom: '25px'
              }}
            >
              <div>
                <Checkbox onChange={this.onChange}>
                  <h7 style={{ fontWeight: 'bold' }}>{kuk_section_name}</h7>
                </Checkbox>
                <br />
                <SectionDetail
                  kuk_section_id={kuk_section_id}
                  checkAll={checkAll}
                />
              </div>
            </div>
          );
        })}

        {/* {payloadKukSection.map(({ kuk_section_id, kuk_section_name }, key) => {
          return (
            <div
              style={{
                borderBottom: '1px solid #E9E9E9',
                marginBottom: '25px'
              }}
            >
              <div>
                <Checkbox
                  indeterminate={indeterminate}
                  onChange={this.onCheckAllChange}
                  checked={checkAll}
                >
                  <h7 style={{ fontWeight: 'bold' }}>{kuk_section_name}</h7>
                </Checkbox>
                <br />
                <CheckboxGroup
                  options={plainOptions}
                  value={this.state.checkedList}
                  onChange={this.onChange}
                />
              </div>
            </div>
          );
        })} */}
        {/* <Checkbox
          indeterminate={this.state.indeterminate}
          onChange={this.onCheckAllChange}
          checked={this.state.checkAll}
        >
          Check All
        </Checkbox>
        <br />
        <CheckboxGroup
          options={plainOptions}
          value={this.state.checkedList}
          onChange={this.onChange}
        /> */}
      </div>
    );
  }
}

export default Bagian1;
