import { mount } from '@vue/test-utils'
import { describe, expect, it } from 'vitest'
import CsvUpload from '../components/CsvUpload.vue'

describe('CsvUpload', () => {
    it('renders properly', () => {
        const wrapper = mount(CsvUpload)
        expect(wrapper.text()).toContain('CSV インポート')
    })

    it('handles file selection', async () => {
        const wrapper = mount(CsvUpload)
        const file = new File(['test'], 'test.csv', { type: 'text/csv' })

        // コンポーネントのメソッドを直接呼び出す
        await wrapper.vm.handleFileUpload({
            target: { files: [file] }
        } as unknown as Event)

        expect(wrapper.vm.file).toBeTruthy()
    })

    it('shows error for invalid file', async () => {
        const wrapper = mount(CsvUpload)
        const file = new File(['test'], 'test.txt', { type: 'text/plain' })

        // コンポーネントのメソッドを直接呼び出す
        await wrapper.vm.handleFileUpload({
            target: { files: [file] }
        } as unknown as Event)

        expect(wrapper.text()).toContain('CSVファイルを選択してください')
    })
})