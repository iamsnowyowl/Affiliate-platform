import React, { Component } from 'react';
import 'antd/dist/antd.css';
import { Upload, Button, Icon, message } from 'antd';
// import Axios from 'axios';

export default class Filing extends Component {
  state = {
    fileList: [],
    uploading: false
  };

  handleUpload = () => {
    const { fileList } = this.state;
    const formData = new FormData();
    fileList.forEach(file => {
      formData.append('files[]', file);
    });

    this.setState({
      uploading: true
    });
  };

  render() {
    const assessment_id = this.props.assessment_id;
    const { uploading } = this.state;
    // const props = {}
    return (
      <div className="animated fadeIn">
        <Upload {...this.props}>
          <Button>
            <Icon type="upload" />
          </Button>
        </Upload>
        <Button
          className="upload-demo-start"
          type="primary"
          onClick={this.handleUpload}
          disable={this.state.fileList === 0}
          loading={uploading}
        >
          {uploading ? 'Uploading' : 'Start Upload'}
        </Button>
      </div>
    );
  }
}
