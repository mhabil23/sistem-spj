<?php

namespace Tests\Feature;

use App\Models\SyaratDokumenSpj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSyaratDokumenTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_syarat_dokumen_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        SyaratDokumenSpj::create([
            'nama_belanja' => 'Belanja Bahan (521211)',
            'jenis_belanja' => 'Belanja Konsumsi',
            'nama_dokumen' => 'Surat Undangan Rapat',
            'assignee' => 'SM',
            'urutan' => 1,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.syarat-dokumen.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Persyaratan Dokumen SPJ');
        $response->assertSee('Surat Undangan Rapat');
    }

    public function test_admin_can_create_new_syarat_dokumen(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $payload = [
            'nama_belanja' => 'Belanja barang persediaan konsumsi (521811)',
            'jenis_belanja' => 'Belanja ATK CS / percetakan dokumen',
            'nama_dokumen' => 'Surat Pengantar BPS',
            'assignee' => 'SM',
            'urutan' => 10,
        ];

        $response = $this->actingAs($admin)->post(route('admin.syarat-dokumen.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('syarat_dokumen_spjs', [
            'nama_belanja' => 'Belanja barang persediaan konsumsi (521811)',
            'jenis_belanja' => 'Belanja ATK CS / percetakan dokumen',
            'nama_dokumen' => 'Surat Pengantar BPS',
            'assignee' => 'SM',
        ]);

        // Verifikasi dokumen langsung muncul di method getSpjData() untuk form frontend
        $data = SyaratDokumenSpj::getSpjData();
        $this->assertArrayHasKey('Belanja barang persediaan konsumsi (521811)', $data);
        $this->assertArrayHasKey('Belanja ATK CS / percetakan dokumen', $data['Belanja barang persediaan konsumsi (521811)']);

        $docNames = array_column($data['Belanja barang persediaan konsumsi (521811)']['Belanja ATK CS / percetakan dokumen'], 'name');
        $this->assertContains('Surat Pengantar BPS', $docNames);
    }

    public function test_admin_can_update_syarat_dokumen(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $doc = SyaratDokumenSpj::create([
            'nama_belanja' => 'Belanja Bahan (521211)',
            'jenis_belanja' => 'Belanja Konsumsi',
            'nama_dokumen' => 'Dokumen Lama',
            'assignee' => 'SM',
            'urutan' => 1,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.syarat-dokumen.update', $doc->id), [
            'nama_belanja' => 'Belanja Bahan (521211)',
            'jenis_belanja' => 'Belanja Konsumsi',
            'nama_dokumen' => 'Dokumen Telah Diperbarui',
            'assignee' => 'Bendahara',
            'urutan' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('syarat_dokumen_spjs', [
            'id' => $doc->id,
            'nama_dokumen' => 'Dokumen Telah Diperbarui',
            'assignee' => 'Bendahara',
        ]);
    }

    public function test_admin_can_delete_syarat_dokumen(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $doc = SyaratDokumenSpj::create([
            'nama_belanja' => 'Belanja Bahan (521211)',
            'jenis_belanja' => 'Belanja Konsumsi',
            'nama_dokumen' => 'Dokumen Akan Dihapus',
            'assignee' => 'SM',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.syarat-dokumen.destroy', $doc->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('syarat_dokumen_spjs', [
            'id' => $doc->id,
        ]);
    }

    public function test_admin_can_create_syarat_dokumen_with_peruntukan_umum_modal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $payload = [
            'nama_belanja' => 'Belanja Modal Peralatan dan Mesin (532111)',
            'jenis_belanja' => 'Pengadaan Laptop dan Komputer PC',
            'nama_dokumen' => 'Surat Pesanan dan BAST Barang Modal',
            'assignee' => 'SM',
            'peruntukan' => 'umum',
            'urutan' => 1,
        ];

        $response = $this->actingAs($admin)->post(route('admin.syarat-dokumen.store'), $payload);
        $response->assertRedirect();

        $this->assertDatabaseHas('syarat_dokumen_spjs', [
            'nama_belanja' => 'Belanja Modal Peralatan dan Mesin (532111)',
            'peruntukan' => 'umum',
        ]);

        // Verifikasi hanya muncul untuk form UMUM, tidak muncul untuk TEKNIS
        $teknisData = SyaratDokumenSpj::getSpjData('teknis');
        $this->assertArrayNotHasKey('Belanja Modal Peralatan dan Mesin (532111)', $teknisData);

        $umumData = SyaratDokumenSpj::getSpjData('umum');
        $this->assertArrayHasKey('Belanja Modal Peralatan dan Mesin (532111)', $umumData);
    }

    public function test_admin_can_filter_syarat_dokumen_by_peruntukan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $docUmum = SyaratDokumenSpj::create([
            'nama_belanja' => 'Belanja Modal Uji Filter (532111)',
            'jenis_belanja' => 'Pengadaan Mesin Uji',
            'nama_dokumen' => 'Dokumen Khusus Umum Unik 53',
            'assignee' => 'SM',
            'peruntukan' => 'umum',
            'urutan' => 1,
        ]);

        $docSemua = SyaratDokumenSpj::create([
            'nama_belanja' => 'Belanja Barang Uji Filter (521111)',
            'jenis_belanja' => 'Operasional Uji',
            'nama_dokumen' => 'Dokumen Bersama Unik 52',
            'assignee' => 'SM',
            'peruntukan' => 'semua',
            'urutan' => 1,
        ]);

        $responseUmum = $this->actingAs($admin)->get(route('admin.syarat-dokumen.index', [
            'peruntukan' => 'umum',
            'search' => 'Uji Filter',
        ]));
        $responseUmum->assertStatus(200);
        $responseUmum->assertSee('Dokumen Khusus Umum Unik 53');
        $responseUmum->assertDontSee('Dokumen Bersama Unik 52');

        $responseSemua = $this->actingAs($admin)->get(route('admin.syarat-dokumen.index', [
            'peruntukan' => 'semua',
            'search' => 'Uji Filter',
        ]));
        $responseSemua->assertStatus(200);
        $responseSemua->assertSee('Dokumen Bersama Unik 52');
        $responseSemua->assertDontSee('Dokumen Khusus Umum Unik 53');
    }
}
