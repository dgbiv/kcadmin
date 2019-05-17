import React from "react";
import styled from "styled-components";
import { LocaleProvider, Form, Radio, Input, Button, message, Icon, Popover } from 'antd';
import ReactDOM from "react-dom";
import zhCN from "antd/lib/locale-provider/zh_CN";
import ClassNames from 'classnames'
import RcUpload from "rc-upload";
import axios from 'axios'
import qs from 'qs';
import '../utils/ajax'
import * as rasterizeHTML from 'rasterizehtml'
import previewStyle from '!!to-string-loader!css-loader!resolve-url-loader!sass-loader?sourceMap!./preview.scss'
import { SketchPicker } from 'react-color'
import Movable from './Movable'

const RadioGroup = Radio.Group;

const {data, appName, qrCodeForWeapp, qrCodeForH5, errorMsg, dataOfDistribution, csrf, isDistributionPage} = window;

const aspectRatio = 2;
const refWidth = 300;
const refHeight = refWidth * aspectRatio;

const exportWidth = 1080;
const exportHeight = exportWidth * aspectRatio;

const codeType = {
    weapp: {
        imageUrl: qrCodeForWeapp,
        text: '小程序码'
    },
    h5: {
        imageUrl: qrCodeForH5,
        text: '二维码'
    }
};

class Spread extends React.Component {
    constructor(props) {
        super(props);

        this.previewRef = React.createRef();

        if (isDistributionPage) {
            const forceData = {
                refWidth,
                refHeight,
                exportWidth,
                exportHeight
            };

            this.state = {
                mode: 'custom',
                type: 'weapp',
                refWidth,
                refHeight,
                exportWidth,
                exportHeight,
                imageUrl: '/img/logo-kcshop.png',
                title: appName,
                description: '这里放入描述文字',
                qrCodePos: {
                    x: 0,
                    y: 0
                },
                qrCodeSize: 100,
                backgroundColor: '#fff',
                ...dataOfDistribution,
                ...forceData
            };


        } else {
            this.state = {
                mode: 'template',
                type: 'weapp',
                refWidth,
                refHeight,
                exportWidth,
                exportHeight,
                imageUrl: '/img/logo-kcshop.png',
                title: appName,
                description: '这里放入描述文字',
                qrCodePos: {
                    x: 0,
                    y: 0
                },
                qrCodeSize: 100,
                backgroundColor: '#fff',
                ...data
            };
        }
    }

    changeType = (e) => {
        this.setState({ type: e.target.value })
    };

    changeMode = (e) => {
        this.setState({ mode: e.target.value })
    };

    handleChange = ({ fileList }) => this.setState({ fileList });

    handleInputChange = (name, e) => {
        this.setState({ [name]: e.target.value })
    };

    handleImageChange = (data) => {
        if (data && (data.status)) {
            this.setState({ imageUrl: data.filename })
        } else if (data.info) {
            message.error(`上传失败：${data.info}`)
        } else if (!data.status) {
            message.error(`上传失败，请稍后重试`)
        } else {
            message.error(`上传失败：${JSON.stringify(data)}`)
        }
    };

    downloadFile = (url) => {
        const downloadElement = document.createElement('a');
        downloadElement.download = 'download';
        downloadElement.href = url;
        downloadElement.click();
    };

    downloadPoster = () => {
        if (this.previewRef && this.previewRef.current) {
            // html2canvas(this.previewRef.current).then((canvas) => {
            //     this.downloadFile(canvas.toDataURL('image/png'));
            // });
            const canvas = document.createElement('canvas');
            canvas.width = 1080;
            canvas.height = canvas.width * aspectRatio;

            const scale = canvas.width / 300;

            const ctx = canvas.getContext('2d');
            ctx.scale(scale, scale);

            rasterizeHTML.drawHTML(this.previewRef.current.outerHTML, canvas).then(() => {
                this.downloadFile(canvas.toDataURL('image/png'));
            })
        }
    };

    downloadQRCode = () => {
        const {type} = this.state;
        this.downloadFile(codeType[type].imageUrl)
    };

    savePoster = () => {
        const url = isDistributionPage ? '' : '/page-layout/spread-setting';
        axios.post(url, {
            '_csrf-api': csrf,
            id: qs.parse(location.search.substr(1)).id,
            content: this.state,
        }).then(() => {
            message.success('保存成功');
        })
    };

    handlePosChange = (x, y) => {
        this.setState({
            qrCodePos: {x, y}
        })
    };

    handleSizeChange = (size) => {
        this.setState({
            qrCodeSize: size
        })
    };

    handleChangeColor = (color) => {
        this.setState({
            backgroundColor: color.hex
        })
    };

    render() {
        const {mode, type, imageUrl, title, description, qrCodePos, qrCodeSize, backgroundColor} = this.state;
        const formItemLayout = {
            labelCol: { span: 3 },
            wrapperCol: { span: 21 },
        };
        const buttonItemLayout = {
            wrapperCol: { span: 16, offset: 3 },
        };

        return (
            <Root>
                {errorMsg ? (
                    <Error>
                        <Icon type="exclamation-circle" theme="filled" style={{fontSize: '6em'}} />
                        <ErrorText>{errorMsg}</ErrorText>
                    </Error>
                ) : (
                    <Container>
                        <PreviewWrapper>
                            <PreviewContainer>
                                <div
                                    ref={this.previewRef}
                                    className={ClassNames('preview', `mode-${mode}`)}
                                    style={{
                                        width: `${refWidth}px`,
                                        height: `${refHeight}px`,
                                        backgroundColor
                                    }}
                                >
                                    <style>{previewStyle}</style>
                                    {mode === 'template' ? (
                                        <>
                                            <div className='preview__main'>
                                                <div className="preview__image-wrapper">
                                                    <img className='preview__image' src={imageUrl} />
                                                </div>
                                                <div className='preview__info'>
                                                    <h1 className='preview__title'>{title}</h1>
                                                    <div className='preview__description'>{description}</div>
                                                </div>
                                            </div>
                                            <div className='preview__footer'>
                                                <img className='preview__circle-image' src={imageUrl} />
                                                <div className='preview__footer-info'>
                                                    <h4 className='preview__app-name'>{appName}</h4>
                                                    <div className='preview__tip'>扫描或长按{codeType[type].text}</div>
                                                </div>
                                                <img className='preview__qr-code' src={codeType[type].imageUrl} />
                                            </div>
                                        </>
                                    ) : (
                                        <>
                                            <img
                                                src={imageUrl}
                                                className={ClassNames('background', {
                                                    'distribution': isDistributionPage
                                                })}

                                            />
                                            <Movable
                                                posX={qrCodePos.x}
                                                posY={qrCodePos.y}
                                                width={qrCodeSize}
                                                height={qrCodeSize}
                                                scale={0.8}
                                                keepAspectRatio
                                                onPosChange={this.handlePosChange}
                                                onSizeChange={this.handleSizeChange}
                                            >
                                                <img
                                                    className='preview__qr-code'
                                                    src={codeType[type].imageUrl}
                                                    draggable="false"
                                                />
                                            </Movable>
                                        </>
                                    )}
                                </div>
                            </PreviewContainer>
                        </PreviewWrapper>
                        <Editor>
                            <Form layout='horizontal'>
                                {!isDistributionPage && (
                                    <Form.Item label='模式' {...formItemLayout}>
                                        <RadioGroup onChange={this.changeMode} value={mode}>
                                            <Radio value='template'>默认模板</Radio>
                                            <Radio value='custom'>自定义</Radio>
                                        </RadioGroup>
                                    </Form.Item>
                                )}
                                <Form.Item label='平台' {...formItemLayout}>
                                    <RadioGroup onChange={this.changeType} value={type}>
                                        <Radio value='weapp'>小程序</Radio>
                                        <Radio value='h5'>H5</Radio>
                                    </RadioGroup>
                                </Form.Item>
                                {/*{mode === 'custom' && (*/}
                                    {/*<>*/}
                                        {/*<Form.Item label='长宽比' {...formItemLayout}>*/}
                                            {/*1:*/}
                                            {/*<Input*/}
                                                {/*value={title}*/}
                                                {/*placeholder='高'*/}
                                                {/*onChange={this.handleInputChange.bind(undefined, 'aspectRatio')}*/}
                                            {/*/>*/}
                                        {/*</Form.Item>*/}
                                    {/*</>*/}
                                {/*)}*/}
                                {mode === 'template' && (
                                    <>
                                        <Form.Item label='标题' {...formItemLayout}>
                                            <Input
                                                value={title}
                                                placeholder='请输入标题'
                                                onChange={this.handleInputChange.bind(undefined, 'title')}
                                            />
                                        </Form.Item>
                                        <Form.Item label='描述' {...formItemLayout}>
                                            <Input
                                                value={description}
                                                placeholder='请输入描述'
                                                onChange={this.handleInputChange.bind(undefined, 'description')}
                                            />
                                        </Form.Item>
                                    </>
                                )}
                                <Form.Item label='图片' {...formItemLayout}>
                                    <Thumbnail>
                                        <RcUpload
                                            action='/page-layout/image-upload'
                                            accept='image/*'
                                            data={{'_csrf-api': csrf}}
                                            onStart={this.onStart}
                                            onSuccess={this.handleImageChange}
                                        >
                                            <UploadedImage
                                                src={imageUrl}
                                                draggable="false"
                                            />
                                            <ImageHandle className='image-handle'>
                                                <Icon type='edit' onClick={this.handlePreview} />
                                                <div>更改图片</div>
                                            </ImageHandle>
                                        </RcUpload>
                                    </Thumbnail>
                                    <ImageTip>海报背景宽高比例 1：2</ImageTip>
                                </Form.Item>
                                {!isDistributionPage && (
                                    <Form.Item label='背景颜色' {...formItemLayout}>
                                        <Popover content={<SketchPicker color={backgroundColor} onChange={this.handleChangeColor} />} >
                                            <Color style={{ backgroundColor }} />
                                        </Popover>
                                    </Form.Item>
                                )}
                                <Form.Item {...buttonItemLayout}>
                                    {!isDistributionPage && (
                                        <>
                                            <Button
                                                type="primary"
                                                onClick={this.downloadPoster}
                                            >下载海报</Button>
                                            <Button
                                                type="primary"
                                                onClick={this.downloadQRCode}
                                                style={{margin: '20px'}}
                                            >仅下载{codeType[type].text}</Button>
                                        </>
                                    )}
                                    <Button
                                        type="primary"
                                        onClick={this.savePoster}
                                    >保存海报布局</Button>
                                </Form.Item>
                            </Form>
                        </Editor>
                    </Container>
                )}
            </Root>
        )
    }
}

const Root = styled.div`
`;

const Container = styled.div`
  display: flex;
  align-items: flex-start;
`;

const Error = styled.div`
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 100%;
  min-height: 400px;
  font-weight: bold;
  color: #f5222d;
`;

const ErrorText = styled.div`
  margin-top: 40px;
  font-weight: bold;
  font-size: 1.5em;
`;

const Editor = styled.div`
  flex-grow: 1;
`;

const PreviewWrapper = styled.div`
  width: ${refWidth * .8 + 40}px;
  height: ${refHeight * .8}px;
`;

const PreviewContainer = styled.div`
  width: ${refWidth + 4}px;
  height: ${refHeight + 4}px;
  margin-left: ${refWidth * -0.1}px;
  margin-top: ${refHeight * -0.1}px;
  border: 2px solid #eee;
  transform: scale(.8);
`;

const Thumbnail = styled.div`
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 150px;
  height: 150px;
  border: 2px solid #eee;
  border-radius: 10px;
  overflow: hidden;
`;

const UploadedImage = styled.img`
  max-width: 150px;
  max-height: 150px;
`;

const ImageHandle = styled.div`
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: rgba(0, 0, 0, .5);
  color: #fff;
  .anticon {
    margin-right: 10px;
    font-size: 1.4em;
    transition: transform .3s;
    cursor: pointer;
    &:active {
      transform: scale(1.6);
    }
  }
`;

const ImageTip = styled.div`
`;

const Color = styled.div`
  width: 100px;
  height: 40px;
  border: 1px solid #ccc;
  border-radius: 5px;
`;

ReactDOM.render(
    <LocaleProvider locale={zhCN}>
        <Spread />
    </LocaleProvider>,
    document.getElementById('spread')
);